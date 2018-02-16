
# Pagar.me + Dokan com Split Payments
Esta é uma integração de Pagar.me + Dokan, com Split Payments que programei para um cliente. Como não encontrei nada parecido com isso disponível na internet, pedi autorização para o cliente para publicar no Github, e ele autorizou.

O Pagar.me é similar ao PagSeguro, porém até o momento em que escrevo este texto, não existe integração gratuita de split payment para PagSeguro com Dokan também.

## Funcionamento
Para realizar um split payment, é necessário informar diversos dados para o Pagar.me. A integração funciona da seguinte forma:

 - Hook no init verifica se o usuário está logado e é um Seller.
 - Se for um Seller, verifica se seus dados de Endereço e Pagamento estão completos. Os dados de endereço são necessários para o cálculo de frete, os dados de pagamentos são necessários para o split payment com o  Pagar.me na hora da concretização da venda.
 - Se os dados estiverem incompletos, redireciona o Seller para um formulário onde ele preenche estes dados.
 - Opcionalmente, é possível enviar um email diário aos sellers com dados incompletos do sistema, solicitando-os que completem seus dados cadastrais.
 - É possível esconder os produtos de sellers com dados bancários e/ou endereço incompletos.
 - Se um comprador tentasse comprar um produto cujo seller não tem os dados bancários cadastrados no sistema, daria erro na hora de enviar a solicitação de split payment para o Pagar.me, logo não é possível adicionar produtos de sellers com dados bancários incompletos no carrinho.

## Instalação
- Baixe o repositório
- Copie o conteúdo dele na pasta do seu tema
- Execute o `composer update` para instalar a API do Pagar.Me
- Abra o arquivo **pagarme_dokan.php** e configure as settings iniciais.
- Adicione isto no seu **functions.php** `require_once(__DIR__.'/pagarme_dokan.php');`

## Requisitos
- [WooCommerce](https://br.wordpress.org/plugins/woocommerce/)
- [WooCommerce Pagar.me](https://br.wordpress.org/plugins/woocommerce-pagarme/)
- [Dokan](https://br.wordpress.org/plugins/dokan-lite/) [1]
- [WooCommerce Correios](https://br.wordpress.org/plugins/woocommerce-correios/) [2]
- [WooCommerce Extra Checkout Fields for Brazil](https://br.wordpress.org/plugins/woocommerce-extra-checkout-fields-for-brazil/) [2]

[1] O cliente em questão possuia o Dokan Lite e o Pro. Creio que o split payment funcione sem o Pro, mas não cheguei a testar.
[2] Gentileza confirmar se é realmente necessário. Fiz este projeto há vários meses atrás.

## Configuração no Pagar.me
- Acesse o dashboard do Pagar.Me em https://dashboard.pagar.me
- No canto superior direito, clique em `{NOME} Ver minha conta`
- No canto inferior esquerdo, clique em `API Keys`
- Copie a `Chave de API` e `Chave de Criptografia` e insira-as nos respectivos campos disponíveis em WooCommerce -> Configuração -> Finalizar Compra -> Pagar.me Cartão de Crédito -> **Chave de API do Pagar.me** e **Chave de Criptografia do Pagar.me**

Importante: As chaves de API são diferentes para teste e para produção. As chaves de API do Pagar.me para produção começam com `ak_live_{API}`/`ek_live_{CRIPTOGRAFIA}`, já as chaves de teste começam com `ak_test_{API}`/`ek_test_{CRIPTOGRAFIA}`.

No seu dashboard do Pagar.me, é possível selecionar a versão da API. Este código foi escrito usando a API versão **2017-07-17**
