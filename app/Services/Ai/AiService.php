<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;

/**
 * Serviço de IA do Advoid (provider-agnóstico; driver atual: Anthropic/Claude).
 * Configure AI_API_KEY no .env. Sem chave, os recursos de IA ficam desligados
 * e os módulos continuam funcionando no modo manual.
 */
class AiService
{
    public function disponivel(): bool
    {
        return filled(config('ai.api_key'));
    }

    /**
     * Lê um comprovante (imagem/PDF rasterizado) e devolve campos de lançamento.
     * @return array{tipo?:string,descricao?:string,valor?:float,vencimento?:?string,forma_pagamento?:?string}
     */
    public function extrairComprovante(string $caminhoAbsoluto, string $mime): array
    {
        $base64 = base64_encode(file_get_contents($caminhoAbsoluto));

        $conteudo = [
            ['type' => 'image', 'source' => ['type' => 'base64', 'media_type' => $mime, 'data' => $base64]],
            ['type' => 'text', 'text' =>
                "Extraia deste comprovante os dados de um lançamento financeiro e responda APENAS com um JSON válido, sem texto fora dele: "
                . '{"tipo":"receita|despesa","descricao":string,"valor":number,"vencimento":"YYYY-MM-DD ou null","forma_pagamento":string ou null}.'],
        ];

        $texto = $this->mensagem($conteudo);
        $json = json_decode($this->isolarJson($texto), true);

        return is_array($json) ? $json : [];
    }

    /**
     * Gera o texto de uma peça jurídica a partir de instruções e do contexto
     * (blocos do DNA, dados do processo). Não inventa fatos.
     */
    public function gerarPeca(string $instrucoes, string $contexto): string
    {
        $system = 'Você é um assistente jurídico que redige peças processuais em português formal, '
            . 'seguindo o estilo e os blocos de argumentação do escritório fornecidos no contexto. '
            . 'Não invente fatos nem jurisprudência: use apenas o que foi fornecido e deixe lacunas marcadas com [PREENCHER] quando faltar informação.';

        $conteudo = [['type' => 'text', 'text' => trim($contexto) . "\n\n--- TAREFA ---\n" . $instrucoes]];

        return $this->mensagem($conteudo, $system);
    }

    protected function mensagem(array $conteudo, ?string $system = null): string
    {
        if (! $this->disponivel()) {
            throw new \RuntimeException('IA não configurada: defina AI_API_KEY no .env.');
        }

        $payload = [
            'model'      => config('ai.model'),
            'max_tokens' => config('ai.max_tokens'),
            'messages'   => [['role' => 'user', 'content' => $conteudo]],
        ];

        if ($system) {
            $payload['system'] = $system;
        }

        $resposta = Http::withHeaders([
            'x-api-key'         => config('ai.api_key'),
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->timeout(90)->post(rtrim(config('ai.base_url'), '/') . '/messages', $payload);

        $resposta->throw();

        return (string) $resposta->json('content.0.text', '');
    }

    protected function isolarJson(string $texto): string
    {
        if (preg_match('/\{.*\}/s', $texto, $m)) {
            return $m[0];
        }

        return $texto;
    }
}
