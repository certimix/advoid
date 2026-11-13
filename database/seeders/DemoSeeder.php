<?php

namespace Database\Seeders;

use App\Models\Atendimento;
use App\Models\CategoriaFinanceira;
use App\Models\Cliente;
use App\Models\Compromisso;
use App\Models\Escritorio;
use App\Models\FaseProcesso;
use App\Models\LancamentoFinanceiro;
use App\Models\ModeloPeca;
use App\Models\Processo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $escritorio = Escritorio::firstOrCreate(
            ['nome' => 'Escritório Advoid'],
            ['cidade' => 'Paripiranga', 'uf' => 'BA', 'ativo' => true]
        );
        $eid = $escritorio->id;

        $admin = User::firstOrCreate(
            ['email' => 'admin@advoid.test'],
            ['escritorio_id' => $eid, 'name' => 'Administrador', 'password' => Hash::make('password'), 'cargo' => 'admin', 'ativo' => true]
        );

        // Fases (Kanban judicial)
        foreach (['Ação Protocolada', 'Com Juiz para Análise', 'Aguardando Manifestação', 'Em Recurso', 'Sentença'] as $i => $nome) {
            FaseProcesso::firstOrCreate(['escritorio_id' => $eid, 'quadro' => 'judicial', 'nome' => $nome], ['ordem' => $i]);
        }
        $fases = FaseProcesso::where('escritorio_id', $eid)->where('quadro', 'judicial')->orderBy('ordem')->get();

        // Categorias financeiras
        $catHon = CategoriaFinanceira::firstOrCreate(['escritorio_id' => $eid, 'nome' => 'Honorários'], ['tipo' => 'receita']);
        $catDesp = CategoriaFinanceira::firstOrCreate(['escritorio_id' => $eid, 'nome' => 'Custas processuais'], ['tipo' => 'despesa']);

        // Clientes
        $clientes = collect([
            ['tipo' => 'fisica', 'nome' => 'Maria Oliveira Santos', 'cpf_cnpj' => '123.456.789-00', 'email' => 'maria@exemplo.com', 'celular' => '(75) 98888-0001', 'cidade' => 'Paripiranga', 'uf' => 'BA'],
            ['tipo' => 'fisica', 'nome' => 'João Pereira Lima', 'cpf_cnpj' => '987.654.321-00', 'email' => 'joao@exemplo.com', 'celular' => '(75) 98888-0002', 'cidade' => 'Adustina', 'uf' => 'BA'],
            ['tipo' => 'juridica', 'nome' => 'Comércio Silva LTDA', 'cpf_cnpj' => '12.345.678/0001-90', 'email' => 'contato@silva.com', 'telefone' => '(75) 3333-0003', 'cidade' => 'Cícero Dantas', 'uf' => 'BA'],
        ])->map(fn ($d) => Cliente::firstOrCreate(
            ['escritorio_id' => $eid, 'nome' => $d['nome']],
            array_merge($d, ['escritorio_id' => $eid, 'ativo' => true])
        ));

        // Processos
        $dadosProc = [
            ['numero_processo' => '0001234-56.2026.8.05.0191', 'area' => 'civel', 'tribunal' => 'TJBA', 'comarca' => 'Paripiranga', 'vara' => '1ª Vara', 'instancia' => '1a', 'valor_causa' => 25000, 'tipo_acao' => 'Ação de indenização'],
            ['numero_processo' => '0007654-32.2026.5.05.0444', 'area' => 'trabalhista', 'tribunal' => 'TRT5', 'comarca' => 'Salvador', 'vara' => '2ª Vara do Trabalho', 'instancia' => '1a', 'valor_causa' => 48000, 'tipo_acao' => 'Reclamação trabalhista'],
            ['numero_processo' => '0009988-77.2026.8.05.0191', 'area' => 'familia', 'tribunal' => 'TJBA', 'comarca' => 'Paripiranga', 'vara' => 'Vara de Família', 'instancia' => '1a', 'valor_causa' => 0, 'tipo_acao' => 'Divórcio consensual'],
        ];
        $processos = collect($dadosProc)->values()->map(function ($d, $i) use ($eid, $admin, $clientes, $fases) {
            $proc = Processo::firstOrCreate(
                ['escritorio_id' => $eid, 'numero_processo' => $d['numero_processo']],
                array_merge($d, [
                    'escritorio_id' => $eid,
                    'cliente_id'    => $clientes[$i % $clientes->count()]->id,
                    'advogado_id'   => $admin->id,
                    'fase_id'       => $fases[$i % $fases->count()]->id,
                    'status'        => 'ativo',
                    'data_distribuicao' => now()->subDays(30 + $i * 10),
                ])
            );

            if ($proc->partes()->count() === 0) {
                $proc->partes()->createMany([
                    ['tipo' => 'autor', 'nome' => $proc->cliente?->nome ?? 'Autor'],
                    ['tipo' => 'reu', 'nome' => 'Parte Contrária ' . ($i + 1)],
                ]);
            }
            if ($proc->movimentacoes()->count() === 0) {
                $proc->movimentacoes()->createMany([
                    ['data' => now()->subDays(20), 'tipo' => 'andamento', 'descricao' => 'Petição inicial protocolada.', 'origem' => 'manual', 'created_by' => $admin->id],
                    ['data' => now()->subDays(8), 'tipo' => 'despacho', 'descricao' => 'Juiz determinou citação da parte ré.', 'origem' => 'manual', 'created_by' => $admin->id],
                ]);
            }
            if ($proc->prazos()->count() === 0) {
                $proc->prazos()->createMany([
                    ['escritorio_id' => $eid, 'responsavel_id' => $admin->id, 'titulo' => 'Apresentar réplica', 'data_prazo' => now()->addDays(5), 'data_fatal' => now()->addDays(7), 'prioridade' => 'alta', 'status' => 'pendente'],
                    ['escritorio_id' => $eid, 'responsavel_id' => $admin->id, 'titulo' => 'Juntar documentos', 'data_prazo' => now()->addDays(12), 'prioridade' => 'media', 'status' => 'pendente'],
                ]);
            }
            return $proc;
        });

        // Atendimentos
        Atendimento::firstOrCreate(
            ['escritorio_id' => $eid, 'assunto' => 'Consulta sobre rescisão de contrato'],
            ['cliente_id' => $clientes[1]->id, 'responsavel_id' => $admin->id, 'area' => 'trabalhista', 'tipo' => 'consultivo', 'origem' => 'whatsapp', 'status' => 'em_andamento', 'descricao' => 'Cliente quer saber se cabe ação após demissão sem justa causa.']
        );
        Atendimento::firstOrCreate(
            ['escritorio_id' => $eid, 'assunto' => 'Cobrança indevida em fatura'],
            ['cliente_id' => $clientes[0]->id, 'responsavel_id' => $admin->id, 'area' => 'civel', 'tipo' => 'contencioso', 'origem' => 'indicacao', 'status' => 'triagem', 'descricao' => 'Possível ação de repetição de indébito.']
        );

        // Agenda
        $compromissos = [
            ['titulo' => 'Audiência de conciliação', 'tipo' => 'audiencia', 'inicio' => now()->addDays(2)->setTime(10, 0), 'local' => 'Fórum de Paripiranga - Sala 1'],
            ['titulo' => 'Julgamento do recurso', 'tipo' => 'julgamento', 'inicio' => now()->addDays(4)->setTime(14, 0), 'local' => 'TJBA'],
            ['titulo' => 'Reunião com cliente', 'tipo' => 'reuniao', 'inicio' => now()->addDay()->setTime(16, 30), 'local' => 'Escritório'],
        ];
        foreach ($compromissos as $i => $c) {
            Compromisso::firstOrCreate(
                ['escritorio_id' => $eid, 'titulo' => $c['titulo']],
                array_merge($c, ['escritorio_id' => $eid, 'responsavel_id' => $admin->id, 'processo_id' => $processos[$i % $processos->count()]->id, 'status' => 'agendado'])
            );
        }

        // Financeiro
        LancamentoFinanceiro::firstOrCreate(
            ['escritorio_id' => $eid, 'descricao' => 'Honorários iniciais - Maria Oliveira'],
            ['tipo' => 'receita', 'valor' => 3500, 'vencimento' => now()->addDays(10), 'status' => 'pendente', 'categoria_id' => $catHon->id, 'cliente_id' => $clientes[0]->id, 'processo_id' => $processos[0]->id, 'responsavel_id' => $admin->id]
        );
        LancamentoFinanceiro::firstOrCreate(
            ['escritorio_id' => $eid, 'descricao' => 'Custas processuais - Reclamação trabalhista'],
            ['tipo' => 'despesa', 'valor' => 420.50, 'vencimento' => now()->subDays(3), 'status' => 'pendente', 'categoria_id' => $catDesp->id, 'processo_id' => $processos[1]->id, 'responsavel_id' => $admin->id]
        );
        LancamentoFinanceiro::firstOrCreate(
            ['escritorio_id' => $eid, 'descricao' => 'Honorários de êxito - Silva LTDA'],
            ['tipo' => 'receita', 'valor' => 8000, 'vencimento' => now()->addDays(25), 'status' => 'pendente', 'categoria_id' => $catHon->id, 'cliente_id' => $clientes[2]->id, 'responsavel_id' => $admin->id]
        );

        // DNA Jurídico
        ModeloPeca::firstOrCreate(
            ['escritorio_id' => $eid, 'titulo' => 'Preliminar de prescrição'],
            ['created_by' => $admin->id, 'area' => 'civel', 'tipo_peca' => 'contestacao', 'categoria' => 'banco_blocos', 'status' => 'ativo', 'conteudo' => "DA PRESCRIÇÃO\n\nPreliminarmente, requer-se o reconhecimento da prescrição, nos termos do art. [PREENCHER] do Código Civil, uma vez que decorrido o prazo legal entre [fato] e o ajuizamento da ação."]
        );
        ModeloPeca::firstOrCreate(
            ['escritorio_id' => $eid, 'titulo' => 'Esqueleto - Petição Inicial Cível'],
            ['created_by' => $admin->id, 'area' => 'civel', 'tipo_peca' => 'peticao_inicial', 'categoria' => 'esqueleto', 'status' => 'ativo', 'conteudo' => "EXCELENTÍSSIMO(A) SENHOR(A) DOUTOR(A) JUIZ(A)...\n\nI - DOS FATOS\nII - DO DIREITO\nIII - DOS PEDIDOS\nIV - DO VALOR DA CAUSA\n\nTermos em que pede deferimento."]
        );

        $this->command?->info('Dados de demonstração criados para o escritório #' . $eid);
    }
}
