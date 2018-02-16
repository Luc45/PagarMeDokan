<?php

/**
*   Define algumas variáveis globais do PagarMe + Dokan
*
*   email_diario_seller_invalido
*       Envia um email diário aos sellers com dados bancários inválidos
*
*   email_seller_quando_visualizar_produto
*       Envia um email ao seller quando um comprador tenta visualizar um de seus produtos, mas não consegue pois o seller está com *       dados incompletos
*
*   email_admin_quando_visualizar_produto_invalido
*       Mesma coisa acima, mas envia um email para o admin do site
*
*   desabilitar_single_de_produtos_seller_incompleto
*       Se o comprador visitar um produto cujo seller está com dados inválidos, impede, redireciona para a home, e tenta enviar *       emails para o seller e para o admin avisando
*
*   desabilitar_listagem_de_produtos_seller_incompleto
*       Impede o produto cujo seller está com dados inválidos de aparecer nas listagens de produtos do site
*
*   proibe_add_carrinho_produto_seller_incompleto
*       Impede que um comprador adicione um item no carrinho de um seller com dados incompletos
*
*/
if ($_SERVER['SERVER_NAME'] == "servidordeproducao.com.br"):
    // Produção
    $settingsDokanPagarMe = [
        'email_diario_seller_invalido' => true,
        'email_seller_quando_visualizar_produto' => true,
        'email_admin_quando_visualizar_produto_invalido' => true,
        'desabilitar_single_de_produtos_seller_incompleto' => true,
        'desabilitar_listagem_de_produtos_seller_incompleto' => false,
        'proibe_add_carrinho_produto_seller_incompleto' => true,
        'id_usuario_pagarmedokan_para_split' => 1, // MUITO IMPORTANTE
        'nome_site' => get_bloginfo('name')
    ];
    error_reporting(0);
    ini_set('display_errors', 0);
else:
    // Desenvolvimento / Homologação
    $settingsDokanPagarMe = [
        'email_diario_seller_invalido' => false,
        'email_seller_quando_visualizar_produto' => false,
        'email_admin_quando_visualizar_produto_invalido' => false,
        'desabilitar_single_de_produtos_seller_incompleto' => true,
        'desabilitar_listagem_de_produtos_seller_incompleto' => false,
        'proibe_add_carrinho_produto_seller_incompleto' => true,
        'id_usuario_pagarmedokan_para_split' => 1, // MUITO IMPORTANTE
        'nome_site' => get_bloginfo('name')
    ];
endif;

/**
*   Função auxiliar para retornar uma lista de bancos com código FEBRABAN
*/
function pegarListaBancos()
{
    // Extraído de https://app.omie.com.br/api/v1/geral/bancos/
    return [
        ["codigo"=>"001","nome"=>"Banco do Brasil","tipo"=>"CB"],["codigo"=>"003","nome"=>"Banco da Amazônia","tipo"=>"CB"],["codigo"=>"004","nome"=>"Banco do Nordeste","tipo"=>"CB"],["codigo"=>"021","nome"=>"Banestes","tipo"=>"CB"],["codigo"=>"025","nome"=>"Alfa","tipo"=>"CB"],["codigo"=>"029","nome"=>"Banerj","tipo"=>"CB"],["codigo"=>"033","nome"=>"Santander","tipo"=>"CB"],["codigo"=>"037","nome"=>"Banpará","tipo"=>"CB"],["codigo"=>"041","nome"=>"Banrisul","tipo"=>"CB"],["codigo"=>"047","nome"=>"Banese","tipo"=>"CB"],["codigo"=>"070","nome"=>"BRB - Banco de Brasília","tipo"=>"CB"],["codigo"=>"077","nome"=>"Banco Intermedium","tipo"=>"CB"],["codigo"=>"083","nome"=>"China Brasil","tipo"=>"CB"],["codigo"=>"084","nome"=>"Uniprime Norte do Paraná","tipo"=>"CB"],["codigo"=>"085","nome"=>"Cecred (Viacredi / CredCrea)","tipo"=>"CB"],["codigo"=>"090","nome"=>"Unicred","tipo"=>"CB"],["codigo"=>"091","nome"=>"Unicred Central do Rio Grande do Sul","tipo"=>"CB"],["codigo"=>"094","nome"=>"Petra","tipo"=>"CB"],["codigo"=>"095","nome"=>"Confidence","tipo"=>"CB"],["codigo"=>"096","nome"=>"Banco BMeF Bovespa","tipo"=>"CB"],["codigo"=>"097","nome"=>"CrediSIS","tipo"=>"CB"],["codigo"=>"099","nome"=>"Uniprime","tipo"=>"CB"],["codigo"=>"102","nome"=>"XP Investimentos","tipo"=>"CB"],["codigo"=>"104","nome"=>"Caixa Econômica Federal","tipo"=>"CB"],["codigo"=>"121","nome"=>"Agiplan","tipo"=>"CB"],["codigo"=>"132","nome"=>"ICBC do Brasil","tipo"=>"CB"],["codigo"=>"136","nome"=>"Centrais Unicreds","tipo"=>"CB"],["codigo"=>"147","nome"=>"Rico","tipo"=>"CB"],["codigo"=>"151","nome"=>"Nossa Caixa","tipo"=>"CB"],["codigo"=>"208","nome"=>"BTG Pactual","tipo"=>"CB"],["codigo"=>"212","nome"=>"Original","tipo"=>"CB"],["codigo"=>"218","nome"=>"Banco Bonsucesso","tipo"=>"CB"],["codigo"=>"224","nome"=>"Fibra","tipo"=>"CB"],["codigo"=>"237","nome"=>"Bradesco","tipo"=>"CB"],["codigo"=>"243","nome"=>"Banco Máxima","tipo"=>"CB"],["codigo"=>"246","nome"=>"Banco ABC Brasil","tipo"=>"CB"],["codigo"=>"250","nome"=>"BCV - Banco de Crédito e Varejo","tipo"=>"CB"],["codigo"=>"254","nome"=>"ParanáBanco","tipo"=>"CB"],["codigo"=>"318","nome"=>"Banco BMG","tipo"=>"CB"],["codigo"=>"320","nome"=>"BIC Banco Industrial e Comercial","tipo"=>"CB"],["codigo"=>"341","nome"=>"Itaú Unibanco","tipo"=>"CB"],["codigo"=>"376","nome"=>"J. P. Morgan","tipo"=>"CB"],["codigo"=>"389","nome"=>"Banco Mercantil do Brasil","tipo"=>"CB"],["codigo"=>"399","nome"=>"HSBC Bank","tipo"=>"CB"],["codigo"=>"422","nome"=>"Safra","tipo"=>"CB"],["codigo"=>"453","nome"=>"Banco Rural S.A.","tipo"=>"CB"],["codigo"=>"456","nome"=>"Banco de Tokyo","tipo"=>"CB"],["codigo"=>"464","nome"=>"Banco Sumitomo Mitsui Brasileiro","tipo"=>"CB"],["codigo"=>"473","nome"=>"Caixa Geral","tipo"=>"CB"],["codigo"=>"600","nome"=>"Banco Luso Brasileiro","tipo"=>"CB"],["codigo"=>"611","nome"=>"Banco Paulista","tipo"=>"CB"],["codigo"=>"612","nome"=>"Banco Guanabara","tipo"=>"CB"],["codigo"=>"637","nome"=>"Sofisa","tipo"=>"CB"],["codigo"=>"643","nome"=>"Pine","tipo"=>"CB"],["codigo"=>"653","nome"=>"Indusval","tipo"=>"CB"],["codigo"=>"654","nome"=>"Banco Renner","tipo"=>"CB"],["codigo"=>"655","nome"=>"Votorantin","tipo"=>"CB"],["codigo"=>"707","nome"=>"Banco Daycoval","tipo"=>"CB"],["codigo"=>"712","nome"=>"Ourinvest","tipo"=>"CB"],["codigo"=>"719","nome"=>"Banif","tipo"=>"CB"],["codigo"=>"735","nome"=>"Neon / Pottencial","tipo"=>"CB"],["codigo"=>"743","nome"=>"Banco Semear","tipo"=>"CB"],["codigo"=>"745","nome"=>"Citibank S.A.","tipo"=>"CB"],["codigo"=>"748","nome"=>"Sicredi","tipo"=>"CB"],["codigo"=>"752","nome"=>"BNP Paribas","tipo"=>"CB"],["codigo"=>"755","nome"=>"Bank of America Merrill Lynch","tipo"=>"CB"],["codigo"=>"756","nome"=>"Sicoob","tipo"=>"CB"],["codigo"=>"897","nome"=>"Mercado Pago","tipo"=>"CV"],["codigo"=>"898","nome"=>"Cresol","tipo"=>"CB"],["codigo"=>"899","nome"=>"Fortumno","tipo"=>"CV"],["codigo"=>"900","nome"=>"PayZen","tipo"=>"CV"],["codigo"=>"901","nome"=>"Vindi","tipo"=>"CV"],["codigo"=>"902","nome"=>"TrayCheckout","tipo"=>"CV"],["codigo"=>"903","nome"=>"ICAP Brasil","tipo"=>"CB"],["codigo"=>"904","nome"=>"UBS Ag","tipo"=>"CB"],["codigo"=>"905","nome"=>"Axis Soluções Financeiras","tipo"=>"CB"],["codigo"=>"906","nome"=>"Dacasa Financeira","tipo"=>"AC"],["codigo"=>"907","nome"=>"Capital Ativo","tipo"=>"CB"],["codigo"=>"908","nome"=>"Sigma Credit","tipo"=>"CB"],["codigo"=>"909","nome"=>"Sumup","tipo"=>"AC"],["codigo"=>"909","nome"=>"Sumup","tipo"=>"AC"],["codigo"=>"910","nome"=>"Credifisco","tipo"=>"CB"],["codigo"=>"911","nome"=>"JCF Factoring","tipo"=>"CB"],["codigo"=>"912","nome"=>"Araguaya","tipo"=>"CB"],["codigo"=>"913","nome"=>"Pague Veloz","tipo"=>"CV"],["codigo"=>"914","nome"=>"CRR Factoring","tipo"=>"CB"],["codigo"=>"915","nome"=>"London Factoring","tipo"=>"CB"],["codigo"=>"916","nome"=>"Globalcash","tipo"=>"CB"],["codigo"=>"917","nome"=>"Target Fomento","tipo"=>"CB"],["codigo"=>"918","nome"=>"Santa Cristina Fomento","tipo"=>"CB"],["codigo"=>"919","nome"=>"RM Fomento","tipo"=>"CB"],["codigo"=>"920","nome"=>"Stripe","tipo"=>"CV"],["codigo"=>"921","nome"=>"Omni Soluções Financeiras","tipo"=>"CB"],["codigo"=>"922","nome"=>"Santana Financeira","tipo"=>"CB"],["codigo"=>"923","nome"=>"Acesso Card","tipo"=>"AC"],["codigo"=>"924","nome"=>"PayU","tipo"=>"CV"],["codigo"=>"925","nome"=>"Global Payments","tipo"=>"AC"],["codigo"=>"926","nome"=>"Payleven","tipo"=>"AC"],["codigo"=>"927","nome"=>"Continental Banco Fomento","tipo"=>"CB"],["codigo"=>"928","nome"=>"SR Bank","tipo"=>"CB"],["codigo"=>"929","nome"=>"Algarve Fomento","tipo"=>"CB"],["codigo"=>"930","nome"=>"RA Fomento","tipo"=>"CB"],["codigo"=>"931","nome"=>"Simetrica Eficiência Financeira","tipo"=>"CB"],["codigo"=>"932","nome"=>"Capital Bank","tipo"=>"CB"],["codigo"=>"933","nome"=>"WidePay","tipo"=>"CV"],["codigo"=>"934","nome"=>"Verde Card","tipo"=>"AC"],["codigo"=>"935","nome"=>"Banricompras","tipo"=>"AC"],["codigo"=>"936","nome"=>"Brasil Pré-Pagos","tipo"=>"AC"],["codigo"=>"937","nome"=>"GerenciaNet","tipo"=>"CV"],["codigo"=>"938","nome"=>"Conta Super","tipo"=>"CB"],["codigo"=>"939","nome"=>"Hotmart","tipo"=>"CV"],["codigo"=>"940","nome"=>"Lecca Financeira","tipo"=>"CB"],["codigo"=>"941","nome"=>"BRR FIDC","tipo"=>"CB"],["codigo"=>"942","nome"=>"Belluno FIDC","tipo"=>"CB"],["codigo"=>"943","nome"=>"Elavon","tipo"=>"AC"],["codigo"=>"944","nome"=>"Grupo Sifra","tipo"=>"CB"],["codigo"=>"945","nome"=>"Work Capital","tipo"=>"CB"],["codigo"=>"946","nome"=>"Iugu","tipo"=>"CV"],["codigo"=>"947","nome"=>"Bcash","tipo"=>"CV"],["codigo"=>"948","nome"=>"Cabal","tipo"=>"AC"],["codigo"=>"949","nome"=>"Payoneer","tipo"=>"CV"],["codigo"=>"950","nome"=>"PagSeguro","tipo"=>"AC"],["codigo"=>"951","nome"=>"Up Plan","tipo"=>"AC"],["codigo"=>"952","nome"=>"Nubank","tipo"=>"AC"],["codigo"=>"953","nome"=>"VR","tipo"=>"AC"],["codigo"=>"954","nome"=>"Cred-System","tipo"=>"AC"],["codigo"=>"955","nome"=>"Alelo","tipo"=>"AC"],["codigo"=>"956","nome"=>"BIN","tipo"=>"AC"],["codigo"=>"957","nome"=>"Stone","tipo"=>"AC"],["codigo"=>"958","nome"=>"Sipag","tipo"=>"AC"],["codigo"=>"959","nome"=>"Google","tipo"=>"CV"],["codigo"=>"960","nome"=>"Green Card","tipo"=>"AC"],["codigo"=>"961","nome"=>"Ticket","tipo"=>"AC"],["codigo"=>"962","nome"=>"Peixe Urbano","tipo"=>"CV"],["codigo"=>"963","nome"=>"iZettle","tipo"=>"AC"],["codigo"=>"964","nome"=>"Groupon","tipo"=>"CV"],["codigo"=>"965","nome"=>"Extra.com.br","tipo"=>"CV"],["codigo"=>"966","nome"=>"Rakuten","tipo"=>"CV"],["codigo"=>"967","nome"=>"Kanui","tipo"=>"CV"],["codigo"=>"968","nome"=>"Moip","tipo"=>"CV"],["codigo"=>"969","nome"=>"Mercado Livre","tipo"=>"CV"],["codigo"=>"970","nome"=>"Visa","tipo"=>"AC"],["codigo"=>"971","nome"=>"Redecard","tipo"=>"AC"],["codigo"=>"972","nome"=>"Hipercard","tipo"=>"AC"],["codigo"=>"973","nome"=>"American","tipo"=>"AC"],["codigo"=>"974","nome"=>"Sorocred","tipo"=>"AC"],["codigo"=>"975","nome"=>"TecBan","tipo"=>"AC"],["codigo"=>"976","nome"=>"CIELO","tipo"=>"AC"],["codigo"=>"977","nome"=>"GetNet","tipo"=>"AC"],["codigo"=>"978","nome"=>"Sodexo","tipo"=>"AC"],["codigo"=>"979","nome"=>"Verocard","tipo"=>"AC"],["codigo"=>"980","nome"=>"JCB","tipo"=>"CR"],["codigo"=>"981","nome"=>"Maestro","tipo"=>"CR"],["codigo"=>"982","nome"=>"Sorocred","tipo"=>"CR"],["codigo"=>"983","nome"=>"Aura","tipo"=>"CR"],["codigo"=>"984","nome"=>"Discover","tipo"=>"CR"],["codigo"=>"985","nome"=>"Visa","tipo"=>"CR"],["codigo"=>"986","nome"=>"MasterCard","tipo"=>"CR"],["codigo"=>"987","nome"=>"American Express","tipo"=>"CR"],["codigo"=>"988","nome"=>"Diners Club","tipo"=>"CR"],["codigo"=>"989","nome"=>"Hipercard","tipo"=>"CR"],["codigo"=>"990","nome"=>"PayPal","tipo"=>"CV"],["codigo"=>"991","nome"=>"PagSeguro","tipo"=>"CV"],["codigo"=>"992","nome"=>"Elo","tipo"=>"CR"],["codigo"=>"993","nome"=>"BNDES","tipo"=>"CR"],["codigo"=>"994","nome"=>"CredSystem","tipo"=>"CR"],["codigo"=>"995","nome"=>"ASAAS","tipo"=>"CV"],["codigo"=>"996","nome"=>"B2W","tipo"=>"CV"],["codigo"=>"997","nome"=>"Walmart","tipo"=>"CV"],["codigo"=>"998","nome"=>"Nova PontoCom","tipo"=>"CV"],["codigo"=>"999","nome"=>"-sem instituição-","tipo"=>"CX"]
    ];
}

/**
*   Roda quando está rodando o wizard de endereço em ?page=dokan-seller-setup&step=store
*/
function alterar_wizard_endereco()
{
    ?>
    <style type="text/css">
        #aviso {
            color:red;
            text-align: center;
            margin-bottom:25px;
        }
    </style>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.12/jquery.mask.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('input#store_ppp').closest('tr').hide(); // Esconde o "Exibir número de itens na loja"
            jQuery('input#show_email').closest('tr').hide(); // Esconde o "Exibir email"
            jQuery('input#enable_shipping').closest('tr').hide(); // Esconde o "Habilitar envio"
            // Remove botão de pular
            jQuery('.wc-setup-actions a.button').hide();
            // Move o CEP para o início
            var cep = jQuery('input#address\\[zip\\]').closest('tr');
            jQuery('tbody').prepend(cep);
            jQuery('input#address\\[zip\\]').mask('00000-000', {placeholder:'00000-000'});
            // Adiciona mensagem de aviso
            jQuery('.dokan-seller-setup-form').prepend('<div id="aviso">O endereço informado abaixo será usado para fins de cálculo de frete. Por favor, preencha com atenção.</div>');
        })
    </script>
 <?php
}
add_action('dokan_seller_wizard_store_setup_field', 'alterar_wizard_endereco', 999);

/**
*   Altera a página de Wizard com javascript para evitar mexer no Dokan
*/
function alterar_wizard_pagamento()
{
    // Recria o form de cadastro de conta bancária
    $bancos = pegarListaBancos();

    // Select de bancos
    $options_bancos = '<select name="settings[bank][bank_name]" class="select2">';
    $options_bancos .= '<option disabled selected value="">- Selecione seu banco -</option>';
    foreach ($bancos as $banco) {
        $options_bancos .= '<option value="'.$banco['codigo'].' - '.$banco['nome'].'">'.$banco['nome'].'</option>';
    }
    $options_bancos .= '</select>'; ?>
        <style type="text/css">
            .dokan-form-group {
                margin: 10px 0;
            }
            .dokan-form-group input {
                padding: 5px 10px;
            }
            textarea.dokan-form-control {
                padding: 5px 10px;
                font-size: 14px;
            }
            .ac_name input.dokan-form-control{
                width:50%;
                display:inline-block;
            }
            #digito input {
                width:45%;
                display:inline-block;
            }
            .dados-bancarios th {
                display: none;
            }
            #aviso {
                color:red;
                text-align: center;
                margin-bottom:25px;
            }
            .wc-setup .wc-setup-actions input[type="submit"].button-primary.desabilitado {
                background: #cecece !important;
                border-color: #848484 !important;
                color: #797979 !important;
                text-shadow: 0px 0px 0px #000 !important;
                cursor: not-allowed;
            }
        </style>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.12/jquery.mask.min.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                /**
                *   Conta bancária
                */
                if ($('tbody').find('tr:first-child').find('label').text().toLowerCase() == 'bank transfer') {
                    $('tbody').find('tr:first-child').addClass('dados-bancarios');

                    // Remove o botão de pular etapa
                    jQuery('.wc-setup-actions a.button').hide();

                    // Adiciona classes para cada input
                    $('.dados-bancarios input[name=settings\\[bank\\]\\[bank_name\\]]').parent('div').addClass('bank_name');
                    $('.dados-bancarios input[name=settings\\[bank\\]\\[ac_name\\]]').parent('div').addClass('ac_name');
                    $('.dados-bancarios input[name=settings\\[bank\\]\\[ac_number\\]]').parent('div').addClass('ac_number');
                    $('.dados-bancarios textarea[name=settings\\[bank\\]\\[bank_addr\\]]').parent('div').addClass('bank_addr');
                    $('.dados-bancarios input[name=settings\\[bank\\]\\[routing_number\\]]').parent('div').addClass('routing_number');
                    $('.dados-bancarios input[name=settings\\[bank\\]\\[iban\\]]').parent('div').addClass('iban');
                    $('.dados-bancarios input[name=settings\\[bank\\]\\[swift\\]]').parent('div').addClass('swift');


                    // Verifica se já está preenchido (é uma edição)
                    var edicao = false;
                    if ($('.bank_name input').val() != "") {
                        edicao = true;
                        var edicao_banco = $('.bank_name input').val();
                        var edicao_tipo_operacao = $('.bank_addr textarea').val();
                    }

                    // Adiciona disabled no campo de continuar até estar tudo preenchido
                    edicao ? $('input[type="submit"]').val('Salvar dados bancários') : $('input[type="submit"]').prop('disabled', 'disabled').addClass('desabilitado').val('Preencha todos os campos...');


                    // Transforma o "nome do banco" num select, e o move para o primeiro lugar
                    var banco = $('.bank_name').detach();
                    $('.dados-bancarios td').prepend(banco);
                    $('.dados-bancarios td').prepend('<div id="aviso">Preencha os campos a seguir com atenção - É com estes dados que iremos depositar os valores referentes aos produtos que você vender em nosso site.</div>');
                    $('.bank_name input').remove();
                    $('.bank_name').append('<?=$options_bancos?>');
                    $('.select2').select2();

                    // Esconde os outros campos até selecionar o banco
                    $('.dokan-form-group').hide();

                    // Executa ações ao selecionar um banco
                    $('.select2').on('change', function() {
                        $('.dokan-form-group').show();

                        // Temos máscara de agência e conta para o banco selecionado?
                        /**
                        *
                        *   Banco           Agência Conta
                        *
                        *   001 - Banco do Brasil 9999-D  99999999-D
                        *   033 - Santander       9999    99999999-D
                        *   104 - Caixa Econômica 9999    XXX99999999-D (X: Operação)
                        *   237 - Bradesco        9999-D  9999999-D
                        *   341 - Itaú            9999    99999-D
                        */
                        var mascaras = [];
                        var aviso = '';
                        var codigo_banco = $(this).val().split('-')[0];
                        codigo_banco = Number(codigo_banco);

                        switch (codigo_banco) {
                            case 001: // Banco do Brasil
                                mascaras = ['0000-9','99999999-9'];
                                break;
                            case 033: // Santander
                                mascaras = ['0000','99999999-9'];
                                break;
                            case 104: // Caixa Econômica Federal
                                mascaras = ['0000','999 99999999-9'];
                                aviso = 'Informe a operação também. Exemplo: XXX 99999999-D (X: Operação)';
                                break;
                            case 237: // Bradesco
                                mascaras = ['0000-9','9999999-9'];
                                break;
                            case 341: // Itaú
                                mascaras = ['0000','99999-9'];
                                break;
                            default:
                                // Força apenas números e hifen na agência e conta, como último caso
                                $('.ac_name input,.ac_number input').on('keyup', function (event) {

                                    //get the newly changed value and limit it to numbers and hyphens
                                    var newValue = this.value.replace(/[^0-9\-]/gi, '');

                                    //if the new value has changed, meaning invalid characters have been removed, then update the value
                                    if (this.value != newValue) {
                                        this.value = newValue;
                                    }
                                })
                        }

                        // Se houver máscara, vamos aplicá-la, etc...
                        if (mascaras.length > 0) {
                            var exemplo_agencia = 'Exemplo: '+mascaras[0].replace(/9/g, '0');
                            var exemplo_conta = 'Exemplo: '+mascaras[1].replace(/9/g, '0');
                            $('.ac_name input').mask(mascaras[0], {placeholder:exemplo_agencia});
                            $('.ac_number input').mask(mascaras[1], {placeholder:exemplo_conta});
                        } else {
                            var exemplo_agencia = 'Exemplo: 0000 ou 0000-0';
                            var exemplo_conta = 'Exemplo: 000000 ou 000000-0';
                        }

                        // Adiciona labels
                        if ($('.ac_name label').length <= 0) {
                            $('.ac_name').prepend('<label></label>');
                            $('.ac_number').prepend('<label></label>');
                            $('.routing_number').prepend('<label></label>');
                            $('.iban').prepend('<label></label>');
                            $('.bank_addr').prepend('<label></label>');
                        }

                        // Adiciona o conteúdo das labels
                        $('.ac_name label').html('Informe sua agência. <small>Se houver dígito verificador, separe por um traço. Exemplo: <strong>'+exemplo_agencia+'</strong></small>');
                        $('.ac_number label').html('Informe sua conta bancária. <small>Se houver dígito verificador, separe por um traço. Exemplo: <strong>'+exemplo_conta+'</strong> <?php echo $aviso?></small>');
                        $('.routing_number label').html('Informe o CPF ou CNPJ do titular da conta');
                        $('.iban label').html('Informe o Favorecido ou Razão Social do titular da conta');
                        $('.bank_addr label').html('Informe o tipo de operação');

                        /**
                        *   MÁSCARAS FIM
                        */

                        // Aproveita alguns campos para usar com outros valores que precisamos

                        // Routing_number -> CPF/CNPJ
                        var campo_cpf_cnpj = $('.routing_number input');

                        $(campo_cpf_cnpj).attr('placeholder', 'CPF ou CNPJ do titular da conta');

                        $(campo_cpf_cnpj).mask("000.000.000-0099999");
                        $(campo_cpf_cnpj).on('keyup', function (e) {

                            var query = $(this).val().replace(/[^a-zA-Z 0-9]+/g,'');;

                            if (query.length <= 11) {
                                $(campo_cpf_cnpj).mask("000.000.000-0099999");
                            } else {
                                $(campo_cpf_cnpj).mask("00.000.000/0000-00");
                            }
                        });

                        // Iban -> Favorecido
                        $('.iban input').attr('placeholder', 'Favorecido ou Razão Social');

                        // bank_addr => Operação
                        $('.bank_addr textarea').remove();
                        $('.select-operacao').select2('destroy');
                        $('.bank_addr select').remove();
                        $('.bank_addr').removeClass('dokan-form-control');

                        $('.bank_addr').addClass('dokan-form-control');
                        $('.bank_addr').append('<select></select>');
                        $('.bank_addr select').addClass('select-operacao');
                        $('.bank_addr select').attr('name', 'settings[bank][bank_addr]');
                        $('.bank_addr select').append('<option disabled selected>- Selecione -</option>');
                        $('.bank_addr select').append('<option value="conta_corrente">Conta Corrente</option>');
                        $('.bank_addr select').append('<option value="conta_poupanca">Conta Poupança</option>');
                        $('.bank_addr select').append('<option value="conta_corrente_conjunta">Conta Corrente Conjunta</option>');
                        $('.bank_addr select').append('<option value="conta_poupanca_conjunta">Conta Poupança Conjunta</option>');
                        $('.select-operacao').select2();

                        $('.swift input').hide();

                        // Limita a quantidade de caracteres de alguns campos
                        $('.ac_name input').attr('maxlength', '10');
                        $('.ac_number input').attr('maxlength', '20');

                    });

                    // Se for edição, vamos inicializar já com os campos expostos
                    edicao ? $('.select2').val(edicao_banco).trigger('change') : null;
                    edicao ? $('.select-operacao').val(edicao_tipo_operacao).trigger('change') : null;

                    /**
                    *   Valida os inputs no blur
                    */
                    $('.dados-bancarios input, .dados-bancarios select, .dados-bancarios textarea').on('blur', function() {
                        // bank_name select -> Banco
                        // ac_name input -> Agência com digito verificador opcional
                        // ac_number input -> Conta com dígito verificador opcional
                        // bank_addr select -> Tipo de operação
                        // routing_number input -> CPF/CNPJ do titular
                        // iban input -> Favorecido
                        // swift input -> Nada
                        if (
                            $('.bank_name select').val() != "" &&
                            $('.ac_name input').val() != "" &&
                            $('.ac_number input ').val() != "" &&
                            $('.bank_addr select').val() != "" &&
                            $('.routing_number input').val() != "" &&
                            $('.iban input').val() != ""
                        ) {
                            $('input[type="submit"]').prop('disabled', false).removeClass('desabilitado').val('Salvar dados bancários');
                        }
                    })
                }
            });
        </script>
    <?php
    if (isset($_SESSION['mensagem_pagamento'])) {
        echo '
        <script type="text/javascript">
            setTimeout(function(){
              alert(\''.$_SESSION["mensagem_pagamento"].'\');
            }, 1000);
        </script>';
        unset($_SESSION['mensagem_pagamento']);
    }
}
add_action('dokan_seller_wizard_payment_setup_field', 'alterar_wizard_pagamento', 999);

/**
*   Função auxiliar que diz se é página de login
*/
function is_login_page()
{
    return in_array($GLOBALS['pagenow'], ['wp-login.php', 'wp-register.php']);
}

/**
*   Retorna true se os dados de pagamento estiverem preenchidos, false caso contrário
*/
function validarDadosPagamentoSeller($seller)
{
    // Temos uma conta bancária cadastrada no PagarMe?
    if (empty(get_user_meta($seller, 'conta_bancaria_pagarme'))) {
        return false;
    }
    return true;
}

/**
*   Retorna true se o endereço estiver preenchido, false caso contrário
*/
function validarEnderecoSeller($seller)
{
    $meta = get_user_meta($seller, 'dokan_profile_settings');
    // Não cadastrou endereço nenhuma vez
    if (!is_array($meta[0]['address'])) {
        return false;
    }
    // Cadastrou mas está incompleto
    foreach ($meta[0]['address'] as $key => $value) {
        // Vamos considerar o "street_2" como não obrigatório
        if ($key != "street_2") {
            if ($value == "") {
                return false;
            }
        }
    }
    return true;
}

/**
*   Retorna true se o endereço e os dados bancários do seller estiverem preenchidos
*/
function validarSeller($seller_id)
{
    if (validarDadosPagamentoSeller($seller_id) && validarEnderecoSeller($seller_id)) {
        return true;
    }
    return false;
}

/**
*   Verifica se o usuário tem uma
*/
function PreCriarContaBancoSellerPagarme($seller)
{
    // Temos. Estamos provavelmente atualizando dados
    $meta = get_user_meta($seller, 'dokan_profile_settings');
    if (!is_array($meta[0]['payment']['bank'])) {
        return false;
    }
    foreach ($meta[0]['payment']['bank'] as $key => $value) {
        // Vamos validar cada campo de acordo com as regras da API do Pagar.me
        // https://docs.pagar.me/v2017-08-28/reference#criando-uma-conta-bancária
        switch ($key) {
            case 'ac_name':
                /**
                *   Agência Bancária + Dígito Verificador
                *
                *   Input esperado: 1234 ou 1234-5
                *   Output esperado: 1234 ou 1234-5
                *
                *   Nome na API Pagar.me: agencia e agencia_dv
                */
                // Vamos separar a agência do digito verificador pelo traço, levando em consideração que não é obrigatório
                if (strpos($value, '-') !== false) {
                    // Tem um digito verificador explicito
                    $agencia_array = explode('-', $value);
                    if (count($agencia_array) !== 2) {
                        // Deve conter apenas um traço. Ex: 1234-5
                        $_SESSION['mensagem_pagamento'] = 'Erro 3467: Agência deve ser 1234 ou 1234-5 (se houver dígito verificador)';
                        return false;
                    }
                    $agencia = $agencia_array[0];
                    $agencia_dv = $agencia_array[1];
                } else {
                    $agencia = $value;
                    $agencia_dv = null; // Não devemos passar $agencia_dv se não houver
                }

                // Temos agência e digito. Vamos verificar se são válidos

                // Agência é numero inteiro?
                if (filter_var($agencia, FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[0-9]+$/"]]) === false) {
                    $_SESSION['mensagem_pagamento'] = 'Erro 3468: Agência deve ser 1234 ou 1234-5 (se houver dígito verificador)';
                    return false;
                }
                // Temos um digito verificador?
                if (isset($agencia_dv)) {
                    // É inteiro?
                    if (filter_var($agencia_dv, FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[0-9]+$/"]]) === false) {
                        $_SESSION['mensagem_pagamento'] = 'Erro 3469: Agência ser 1234 ou 1234-5 (se houver dígito verificador)';
                        return false;
                    }
                    // Dígito tem no máximo 1 digitos?
                    if (strlen($agencia_dv) > 1) {
                        $_SESSION['mensagem_pagamento'] = 'Erro 3470: Dígito verificador da agência só pode ter 1 número';
                        return false;
                    }
                }
                // Agência tem no máximo 5 digitos?
                if (strlen($agencia) > 5) {
                    $_SESSION['mensagem_pagamento'] = 'Erro 3471: Agência não pode ter mais do que 5 dígitos (sem contar traço e digito verificador)';
                    return false;
                }
                // Se chegamos até aqui, temos uma agência e digitos válidos
                break;

            case 'ac_number':
                /**
                *   Conta Bancária + Dígito Verificador
                *
                *   Input esperado: 12345678 ou 12345678-9
                *   Output esperado: 12345678-0 ou 12345678-9
                *
                *   Nome na API Pagar.me: conta e conta_dv
                */
                // Vamos separar a conta do digito verificador pelo traço, levando em consideração que não é obrigatório
                //echo $value;exit;
                if (strpos($value, '-') !== false) {
                    // Tem um digito verificador explicito
                    $conta_array = explode('-', $value);
                    if (count($conta_array) !== 2) {
                        // Deve conter apenas um traço. Ex: 12345678-5
                        $_SESSION['mensagem_pagamento'] = 'Erro 3472: Conta Bancária deve ser um numero de até 8 caracteres, com traço opcional para dígito. Exemplo: 12345678 ou 1234-5';
                        return false;
                    }
                    $conta = $conta_array[0];
                    $conta_dv = $conta_array[1];
                } else {
                    $conta = $value;
                    $conta_dv = 0; // Já o dígito da conta é obrigatório
                }

                // Remove leading zeros do integer
                $conta = ltrim($conta, 0);

                // Substitui tudo que não é numeral da conta
                $conta = preg_replace('/[^0-9]/', '', $conta);

                // Temos conta e digito. Vamos verificar se são válidos

                // Conta é número inteiro?
                if (filter_var($conta, FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[0-9]+$/"]]) === false) {
                    $_SESSION['mensagem_pagamento'] = 'Erro 3473: Conta Bancária deve ser um numero de até 8 caracteres, com traço opcional para dígito. Exemplo: 12345678 ou 1234-5';
                    return false;
                }
                // Dígito da conta é número inteiro?
                if (filter_var($conta_dv, FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[0-9]+$/"]]) === false) {
                    $_SESSION['mensagem_pagamento'] = 'Erro 3474: Conta Bancária deve ser um numero de até 8 caracteres, com traço opcional para dígito. Exemplo: 12345678 ou 1234-5';
                    return false;
                }
                // Conta tem no máximo 13 digitos?
                if (strlen($conta) > 13) {
                    $_SESSION['mensagem_pagamento'] = 'Erro 3475: Conta Bancária deve ter no máximo 13 números, excluindo o traço e o digito verificador.';
                    return false;
                }
                // Dígito tem no máximo 2 digitos?
                if (strlen($conta_dv) > 2) {
                    $_SESSION['mensagem_pagamento'] = 'Erro 3476: Dígito da Conta Bancária deve ter no máximo 2 números';
                    return false;
                }
                // Se chegamos até aqui, temos uma conta e digitos válidos
                break;

            case 'bank_name':
                /**
                *   ID do Banco (Input: 341 - Itaú Unibanco | Output: 341)
                *
                *   Input esperado: 341 - Itaú Unibanco
                *   Output esperado: 341
                *
                *   Nome na API Pagar.me: bank_code
                */
                // Vamos pegar apenas o código bancário
                if (strpos($value, '-') !== false) {
                    $bank_code = explode('-', $value);
                    $bank_code = $bank_code[0];
                    $bank_code = trim($bank_code);
                } else {
                    $_SESSION['mensagem_pagamento'] = 'Erro 3477: Por favor, escolha um banco válido.';
                    return false;
                }
                // É um número inteiro?
                if (filter_var($bank_code, FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[0-9]+$/"]]) === false) {
                    $_SESSION['mensagem_pagamento'] = 'Erro 3478: Por favor, escolha um banco válido.';
                    return false;
                }
                // Tem 3 dígitos?
                if (strlen($bank_code) !== 3) {
                    $_SESSION['mensagem_pagamento'] = 'Erro 3479: Por favor, escolha um banco válido.';
                    return false;
                }
                // Se chegamos até aqui, temos um código bancário válido
                break;

            case 'bank_addr':
                /**
                *   Tipo de conta
                *
                *   Input e Output: conta_corrente, conta_poupanca, conta_corrente_conjunta, conta_poupanca_conjunta
                *
                *   Nome na API Pagar.me: type
                */
                $array = ['conta_corrente', 'conta_poupanca', 'conta_corrente_conjunta', 'conta_poupanca_conjunta'];
                if (!in_array($value, $array)) {
                    $_SESSION['mensagem_pagamento'] = 'Erro 3480: Por favor, selecione o tipo de operação.';
                    return false;
                } else {
                    $type = $value;
                }
                break;
            case 'routing_number':
                /**
                *   CPF / CNPJ
                *
                *   Input esperado: 000.000.000-00 ou 00.000.000/0000-00
                *   Output esperado: 00000000000 ou 00000000000000
                *
                *   Nome na API Pagar.me: document_number
                */
                $value = preg_replace('/[^0-9]/', '', $value);
                if (strlen($value) !== 11 && strlen($value) !== 14) {
                    $_SESSION['mensagem_pagamento'] = 'Erro 3481: Por favor, informe um CPF ou CNPJ válido.';
                    return false;
                } else {
                    $document_number = $value;
                }
                break;
            case 'iban':
                /**
                *   Favorecido
                *
                *   Input esperado: String com nome da pessoa ou empresa
                *   Output esperado: String até 30 caracteres
                *
                *   Nome na API Pagar.me: legal_name
                */
                $legal_name = substr($value, 0, 30);
                break;
            case 'swift':
                // Campo não utilizado, deve permanecer vazio
                $value = '';
                break;

            default:
                return false;
                break;
        }
    }

    // Temos dados válidos. Cria a conta no Pagar.Me
    return criarContaBancoSellerPagarMe($seller, $bank_code, $agencia, $agencia_dv, $conta, $conta_dv, $type, $document_number, $legal_name);
}

/**
*   Cria uma conta bancária para um seller no Pagar.me
*/
function criarContaBancoSellerPagarMe($seller, $bank_code, $agencia, $agencia_dv, $conta, $conta_dv, $type, $document_number, $legal_name)
{
    // Cadastra uma nova conta bancária no Pagar.me
    require_once('vendor/autoload.php');
    $pagarme_options = get_option('woocommerce_pagarme-credit-card_settings');
    // Vamos tentar pegar a API do banco, caso contrário vamos setar arbitrariamente
    if (is_array($pagarme_options)) {
        if (array_key_exists('api_key', $pagarme_options)) {
            if ($pagarme_options['api_key'] != "") {
                $apiKey = $pagarme_options['api_key'];
            }
        }
    }
    if (!isset($apiKey)) {
        $apiKey = 'ak_test_flvf2vgyplmI0oEhA9tHPuODUKhcTF';
    }
    $pagarMe =  new \PagarMe\Sdk\PagarMe($apiKey);
    //$bankAccountList = $pagarMe->bankAccount()->getList();
    /**
    *   Cria uma conta bancária
    *   https://github.com/pagarme/pagarme-php/wiki/Contas-Banc%C3%A1rias#cadastrar-uma-nova-conta-banc%C3%A1ria
    */
    $bankAccount = $pagarMe->bankAccount()->create(
        $bank_code,
        $agencia,
        $conta,
        $conta_dv,
        $document_number,
        $legal_name,
        $agencia_dv,
        $type
    );
    $idBankAccount = $bankAccount->getId();
    if (is_numeric($idBankAccount)) {
        // Temos um ID de Pagar.me. Temos que atualizar um meta existente ou adicionar um novo?
        if (empty(get_user_meta($seller, 'conta_bancaria_pagarme'))) {
            add_user_meta($seller, 'conta_bancaria_pagarme', $idBankAccount);
        } else {
            update_user_meta($seller, 'conta_bancaria_pagarme', $idBankAccount);
        }
    } else {
        update_user_meta($seller, 'conta_bancaria_pagarme', null);
    }
}
//var_dump(get_user_meta(get_current_user_id(), 'conta_bancaria_pagarme'));exit;

/**
*   Função auxiliar para informar se um UserID é um seller de dokan
*/
function usuario_is_seller_dokan($user_id)
{
    if (! user_can($user_id, 'dokandar')) {
        return false;
    }

    return true;
}

/**
*   Exibe o Wizard se for Seller e estiver sem dados bancários ou endereço cadastrados
*/
function wizard_corrigir_dados_bancarios()
{
    // Usuário é Seller?
    if (usuario_is_seller_dokan(get_current_user_id()) && !wp_doing_ajax() && !isset($_GET['wc-ajax'])) {
        // Endereço incompleto?
        if ($_GET['page'] != 'dokan-seller-setup' && !is_admin() && !is_login_page()) {
            if (!validarEnderecoSeller(get_current_user_id())) {
                wp_redirect(home_url().'?page=dokan-seller-setup&step=store', 302);
                exit;
            }
        }
        // Dados bancários incompletos?
        if ($_GET['page'] != 'dokan-seller-setup' && !is_admin() && !is_login_page()) {
            if (!validarDadosPagamentoSeller(get_current_user_id())) {
                wp_redirect(home_url().'?page=dokan-seller-setup&step=payment', 302);
                exit;
            }
        }
    }
}
add_action('init', 'wizard_corrigir_dados_bancarios', -1);

/**
*   Roda quando salva o endereço em ?page=dokan-seller-setup&step=store
*/
function salvar_endereco()
{
    if (!validarEnderecoSeller(get_current_user_id())) {
        wp_redirect(home_url().'?page=dokan-seller-setup&step=store', 302);
        exit;
    }
}
add_action('dokan_seller_wizard_store_field_save', 'salvar_endereco');

/**
*   Roda quando salva os dados bancários em ?page=dokan-seller-setup&step=payment
*/
function salvar_dados_bancarios()
{
    // Cria uma conta no PagarMe para o Seller com os dados que ele informou
    PreCriarContaBancoSellerPagarme(get_current_user_id());

    // Certifica que os dados foram preenchidos
    if (!validarDadosPagamentoSeller(get_current_user_id())) {
        wp_redirect(home_url().'?page=dokan-seller-setup&step=payment', 302);
        exit;
    }

    // Cadastra um novo recebedor no Pagar.me
    require_once('vendor/autoload.php');
    $pagarme_options = get_option('woocommerce_pagarme-credit-card_settings');
    // Vamos tentar pegar a API do banco, caso contrário vamos setar arbitrariamente
    if (is_array($pagarme_options)) {
        if (array_key_exists('api_key', $pagarme_options)) {
            if ($pagarme_options['api_key'] != "") {
                $apiKey = $pagarme_options['api_key'];
            }
        }
    }
    if (!isset($apiKey)) {
        $apiKey = '';
    }
    $pagarMe =  new \PagarMe\Sdk\PagarMe($apiKey);

    /**
    *   Cria um recipiente
    *   https://github.com/pagarme/pagarme-php/wiki/Recebedores#criando-um-recebedor
    */
    $bankAccount = get_user_meta(get_current_user_id(), 'conta_bancaria_pagarme');
    $bankAccount = $pagarMe->bankAccount()->get($bankAccount[0]);
    $recipient = $pagarMe->recipient()->create(
        $bankAccount,
        'daily',
        0,
        true
    );
    $idRecipiente = $recipient->getId();
    if (isset($idRecipiente)) {
        // Temos um ID de Pagar.me. Temos que atualizar um meta existente ou adicionar um novo?
        if (empty(get_user_meta(get_current_user_id(), 'id_recipiente_pagarme'))) {
            add_user_meta(get_current_user_id(), 'id_recipiente_pagarme', $idRecipiente);
        } else {
            update_user_meta(get_current_user_id(), 'id_recipiente_pagarme', $idRecipiente);
        }
    } else {
        update_user_meta(get_current_user_id(), 'id_recipiente_pagarme', null);
    }
}
add_action('dokan_seller_wizard_payment_field_save', 'salvar_dados_bancarios');

/**
*   Exibe apenas produtos de sellers com dados bancários válidos
*
*/
// Listagem de produtos
function listagem_de_produtos_woocommerce($visible, $this_get_id)
{
    $produto = wc_get_product($this_get_id);
    return validarSeller($produto->post->post_author);
}
// Single page
function single_page_produto_woocommerce()
{
    $produto = wc_get_product(get_the_ID());
    if (!validarSeller($produto->post->post_author)) {
        echo '<script>window.location = "'.home_url().'?problemasVendedor='.$produto->post->post_author.'"</script>';
    }
}
if ($settingsDokanPagarMe['desabilitar_listagem_de_produtos_seller_incompleto']):
    add_filter('woocommerce_product_is_visible', 'listagem_de_produtos_woocommerce', 10, 2);
endif;
if ($settingsDokanPagarMe['desabilitar_single_de_produtos_seller_incompleto']):
    add_filter('woocommerce_single_product_summary', 'single_page_produto_woocommerce', 12, 1);
endif;

/**
*   Função auxiliar para avisar o comprador de que visitou um produto com dados de vendedor inválido
*/
function aviso_problema_vendedor()
{
    if (isset($_GET['problemasVendedor']) && !wp_doing_ajax() && !isset($_GET['wc-ajax'])):

    $vendedor = get_userdata($_GET['problemasVendedor']);
    $email_pagarmedokan = get_bloginfo('admin_email');

    // Envia um email para o dono do site avisando
    if ($settingsDokanPagarMe['email_admin_quando_visualizar_produto_invalido']):
            wp_mail($email_pagarmedokan, 'Um comprador tentou visualizar o produto de um vendedor que está com dados de pagamento e/ou endereço incompletos no '.$settingsDokanPagarMe['nome_site'], 'Olá. Alguém tentou visualizar um produto no '.$settingsDokanPagarMe['nome_site'].', porém não conseguiu concretizar a compra pois os dados de endereço e/ou bancários do vendedor estão incompletos em nosso site.<br><br>
                ID do Vendedor: '.$_GET['problemasVendedor'].'<br>
                Nome do Vendedor: '.$vendedor->first_name.' '.$vendedor->last_name.'<br>
                E-mail do Vendedor: '.$vendedor->user_email.'<br><br>O vendedor acaba de ser notificado por email.');
    endif;

    // Envia um email para o vendedor
    if ($settingsDokanPagarMe['email_seller_quando_visualizar_produto']):
            wp_mail($vendedor->user_email, 'Dados de pagamento e/ou endereço incompletos no '.$settingsDokanPagarMe['nome_site'], 'Olá. Alguém tentou visualizar um dos seus produtos no '.$settingsDokanPagarMe['nome_site'].', porém não conseguiu concretizar a compra pois seus dados de endereço e/ou bancários estão incompletos em nosso site. Para poder vender, por favor, complete seu cadastro clicando neste link: <a href="'.home_url().'?page=dokan-seller-setup&step=store">'.home_url().'?page=dokan-seller-setup&step=store</a>');
    endif;

    echo '<script>
                alert("Desculpe, você tentou visualizar um produto que está temporariamente desabilitado.");
                window.location = "'.home_url().'"
              </script>';
    endif;
}
add_filter('init', 'aviso_problema_vendedor', 10, 1);

/**
*   Cadastra uma função para rodar diariamente
*/
function cadastra_hook_enviar_email_dados_incompletos_sellers()
{
    if (! wp_next_scheduled('rodar_diariamente')) {
        wp_schedule_event(time(), 'daily', 'enviar_email_dados_incompletos_sellers');
    }
}
/**
*   Envia um e-mail diariamente para os sellers solicitando que eles completem seu cadastro
*/
function enviar_email_dados_incompletos_sellers()
{
    // Pega uma lista de todos os sellers ATIVOS
    $sellers = dokan_get_sellers(-1);

    // Faz o envio
    foreach ($sellers['users'] as $sellerObj) {
        // Está com endereço e dados bancários preenchidos?
        if (!validarSeller($sellerObj->ID)) {
            $seller = get_userdata($sellerObj->ID);
            wp_mail($seller->user_email, 'Dados de pagamento e/ou endereço incompletos no '.$settingsDokanPagarMe['nome_site'], 'Olá. Para poder vender no '.$settingsDokanPagarMe['nome_site'].', você precisa completar seu cadastro informando endereço e dados bancários. <br><br>Usamos estes dados para calcular o frete e depositar para você o valor de suas vendas.<br><br> Por favor, complete seu cadastro clicando neste link: <a href="'.home_url().'?page=dokan-seller-setup&step=store">'.home_url().'?page=dokan-seller-setup&step=store</a>');
        }
    }
}
if ($settingsDokanPagarMe['email_diario_seller_invalido']):
    add_action('wp', 'cadastra_hook_enviar_email_dados_incompletos_sellers');
    add_action('rodar_diariamente', 'enviar_email_dados_incompletos_sellers');
endif;


/**
*   Função auxiliar para enviar emails como HTML
*/
function emails_wordpress_html()
{
    return "text/html";
}
add_filter('wp_mail_content_type', 'emails_wordpress_html');

/**
*   Impede que um comprador adicione no carrinho produtos de um seller que está com dados incompletos
*/
function proibe_add_carrinho_produto_seller_incompleto($passed, $product_id, $quantity, $variation_id = '', $variations= '')
{
    $produto = wc_get_product($product_id);
    return validarSeller($produto->post->post_author);
}
if ($settingsDokanPagarMe['proibe_add_carrinho_produto_seller_incompleto']):
    add_action('woocommerce_add_to_cart_validation', 'proibe_add_carrinho_produto_seller_incompleto', 10, 5);
endif;

/**
*   Recebe um objeto WC_Order e retorna um array com o valor que cada seller receberá desta venda, incluindo a comissão do admin do site
*/
function pegarValorDeCadaSellerPorVenda($order)
{
    global $settingsDokanPagarMe;

    /**
    *   Adiciona o valor do produto para cada seller
    */
    $items = $order->get_items();
    $valor_total_por_seller = [];
    foreach ($items as $key => $produto) {

        // Pega o ID do item
        $id_item = $produto->get_product_id();

        // Pega o seller deste item específico
        $id_seller = get_post_field( 'post_author', $id_item );

        /**
        *   Separa a comissão do admin do site
        */
        $preco_produto = $produto->get_total();
        $porcentagem_comissao_pagarmedokan = dokan_get_option('admin_percentage', 'dokan_selling', 10);

        $valor_comissao_pagarmedokan = ($preco_produto/100)*$porcentagem_comissao_pagarmedokan;

        // Calcula quanto vai pro admin do site
        $valor_total_por_seller[$settingsDokanPagarMe['id_usuario_pagarmedokan_para_split']] = $valor_total_por_seller[$settingsDokanPagarMe['id_usuario_pagarmedokan_para_split']] + bcmul($valor_comissao_pagarmedokan, 100);

        // Calcula quanto vai pros Sellers
        $total_sellers = $preco_produto-$valor_comissao_pagarmedokan;

        // Adiciona o valor ao array do seller
        $valor_total_por_seller[$id_seller] = $valor_total_por_seller[$id_seller] + bcmul($total_sellers, 100);
    }
    /**
    *   Adiciona o valor do frete para cada seller
    */
    foreach ($order->get_items('shipping') as $shipping) {
        $custo_frete = $shipping->get_total();
        $id_seller = $shipping->get_meta('seller_id');
        $valor_total_por_seller[$id_seller] = $valor_total_por_seller[$id_seller] + bcmul($custo_frete, 100);
    }
    /**
    *   Certifica que a soma das porcentagens é igual a 100, se não for, distribui o restante
    *   @deprecated porquê estamos usando amount, não percentage
    */
    //$is_porcentagem = false;
    //if ($is_porcentagem) {
    //    $porcentagem_total = 0;
    //    foreach ($valor_total_por_seller as $key => $value) {
    //        (int) $porcentagem_total += (int) $value;
    //    }
    //    if ($porcentagem_total !== 100) {
    //        $porcentagem_que_falta = 100 - $porcentagem_total;
    //    }
    //    // Vamos tentar dividir igualmente
    //    if ($porcentagem_que_falta % count($valor_total_por_seller) == 0) {
    //        foreach ($valor_total_por_seller as $key => &$value) {
    //            (int) $value += $porcentagem_que_falta / count($valor_total_por_seller);
    //        }
    //    } else {
    //        // Se não der, vamos adicionar o que sobre à comissão do admin do site
    //        (int) $valor_total_por_seller[$settingsDokanPagarMe['id_usuario_pagarmedokan_para_split']] += $porcentagem_que_falta;
    //    }
    //}
    return $valor_total_por_seller;
}

/**
*   Função adicional que recebe um array e retorna a porcentagem do valor total com RecipientID
*/
function retornaPagarMeSplitArray($valorPorSeller)
{
    $total = array_sum($valorPorSeller);
    $array = [];
    $i = 0;
    foreach ($valorPorSeller as $seller => $valor) {
        // Impede que seja realizada uma transação se um dos sellers tiver dados bancários inválidos
        if (!is_array(get_user_meta($seller, 'id_recipiente_pagarme'))) {
            $vendedor = get_userdata($seller);
            $email_pagarmedokan = get_bloginfo('admin_email');
            wp_mail($email_pagarmedokan, 'Um comprador tentou pagar o produto de um vendedor que está com dados de pagamento e/ou endereço incompletos no '.$settingsDokanPagarMe['nome_site'], 'Olá. Alguém tentou comprar um produto na '.$settingsDokanPagarMe['nome_site'].', porém não conseguiu concretizar a compra pois os dados de endereço e/ou bancários do vendedor estão incompletos em nosso site.<br><br>
                ID do Vendedor: '.$seller.'<br>
                Nome do Vendedor: '.$vendedor->first_name.' '.$vendedor->last_name.'<br>
                E-mail do Vendedor: '.$vendedor->user_email.'<br><br>O vendedor não foi notificado por email.');
            return false;
        }
        $array[$i]['amount'] = $valor;
        $array[$i]['recipient_id'] = get_user_meta($seller, 'id_recipiente_pagarme', true);
        $array[$i]['liable'] = true;
        $array[$i]['charge_processing_fee'] = true;
        $i++;
    }
    return $array;
}

/**
 * Slip rules for Pagar.me.
 *
 * @param  array    $data  Transacion data.
 * @param  WC_Order $order Order instance.
 * @return array
 */
function wc_pagarme_slip_rules($data, $order)
{

    // Pega quanto cada seller vai receber na venda
    $valorPorSeller = pegarValorDeCadaSellerPorVenda($order);

    // Transforma os valores em porcentagem
    $arraySplitPagarMe = retornaPagarMeSplitArray($valorPorSeller);

    // Split rules para Cartão de crédito e Boleto
    if ($order->get_payment_method() == 'pagarme-credit-card' || $order->get_payment_method() == 'pagarme-banking-ticket') {
        $data['split_rules'] = $arraySplitPagarMe;
    }

    return $data;
}

add_action('wc_pagarme_transaction_data', 'wc_pagarme_slip_rules', 10, 2);

/**
*   Exibe uma mensagem pro admin caso o Admin que receba a comissão esteja com dados de pagamento inválidos
*/
function verifica_dados_pagarme_usuario_pagarmedokan() {
    global $settingsDokanPagarMe;

    if (!validarDadosPagamentoSeller($settingsDokanPagarMe['id_usuario_pagarmedokan_para_split'])) {

        $usuario_pagarmedokan = get_userdata($settingsDokanPagarMe['id_usuario_pagarmedokan_para_split']);
        echo '<div class="notice notice-error">MUITA ATENÇÃO! O USUÁRIO CONFIGURADO PARA RECEBER AS COMISSÕES DO SPLIT PAYMENT ESTÁ COM DADOS DE PAGAMENTO INVÁLIDOS, ISSO IMPEDE QUALQUER TRANSAÇÃO DO SITE!</div>';

    }
}
add_action( 'admin_notices', 'verifica_dados_pagarme_usuario_pagarmedokan', 10, 1 );
