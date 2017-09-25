# WHMCS-NFe.io

ATENÇÃO: NÃO desative seu módulo em Opções -> Módulos Addon. Isso acarretará na remoção de todas as notas fiscais salvas em seu WHMCS.

## Instalação
- Incluir no php.ini as configurações
```php
    allow_url_fopen = ON
    allow_url_include = ON
```
- Envie todas as pastas e documentos para a raiz do WHMCS.
- Acesse o admin do WHMCS.
- Entre em Opções -> Módulos Addon.
- Encontre o addon NFe.io e clique em Ativar.
- Clique em Configure e, em Controle de Acesso, marque o grupo de administradores que poderão gerenciar o módulo.
- Entre em Addons -> NFe.io e em seguida clique na aba Configurações.
- Preencha corretamente todos os ítens. No caso dos campos personalizados (Campo CPF/CNPJ, Campo Número, Campo Complemento e Campo Emitir NF), caso os mesmo já não existam cadastrados, basta clicar na opção "clique aqui para criar" localizado no final da descrição de cada ítem e o mesmo será criado e selecionado automaticamente.
- Após salvar corretamente as configurações, acesse o perfil cadastral de cada cliente e preencha os campos criados. Os campos CPF/CNPJ, Número e Emitir NF são de preenchimento obrigatório.
- No caso do campo Emitir NF, por se tratar de um campo "select", o mesmo deverá ter uma opção escolhida pra cada cliente. Por padrão ele vem pré-selecionado "Nenhum" em todos os clientes. Isso significa que não será gerada nenhuma nota fiscal. Para que a nota fiscal seje gerada, basta escolher uma dar opções, se será gerada ao criar a fatura ou ao pagamento da fatura.
- As notas fiscais poderão ser visualizadas em Addons -> NFe.io.
- Acesse o painel da NFe.io, clique em Conta, clique na aba Webhook localizado no submenu e em seguida Criar Webhook (https://app.nfe.io/hooks/new).
- Em Endereço (URL) informe a URL de retorno do seu WHMCS (http://www.seu_whmcs.com.br/modules/addons/nfeio/webhook.php) substituindo www.seu_whmcs.com.br pelo endereço do seu WHMCS e clique em Salvar.

## Atualização
- Envie todas as pastas e documentos para a raiz do WHMCS substituindo os atuais.
- Exclua do seu WHMCS alguns arquivos/pastas da versão anterior:
  /seu-whmcs/includes/hooks/lib
  /seu-whmcs/includes/hooks/hook_nfeio_InvoicePaid.php
  /seu-whmcs/modules/widget/nfeio.php
- Acesse o admin do WHMCS.
- Entre em Opções -> Módulos Addon.
- Clique em Configure e, em Controle de Acesso, marque o grupo de administradores que poderão gerenciar o módulo.
- Entre em Addons -> NFe.io e em seguida clique na aba Configurações.
- Preencha corretamente todos os ítens. No caso dos campos personalizados (Campo CPF/CNPJ, Campo Número, Campo Complemento e Campo Emitir NF), caso os mesmo já não existam cadastrados, basta clicar na opção "clique aqui para criar" localizado no final da descrição de cada ítem e o mesmo será criado e selecionado automaticamente.
- Após salvar corretamente as configurações, acesse o perfil cadastral de cada cliente e preencha os campos criados. Os campos CPF/CNPJ, Número e Emitir NF são de preenchimento obrigatório.
- No caso do campo Emitir NF, por se tratar de um campo "select", o mesmo deverá ter uma opção escolhida pra cada cliente. Por padrão ele vem pré-selecionado "Nenhum" em todos os clientes. Isso significa que não será gerada nenhuma nota fiscal. Para que a nota fiscal seje gerada, basta escolher uma dar opções, se será gerada ao criar a fatura ou ao pagamento da fatura.
- As notas fiscais poderão ser visualizadas em Addons -> NFe.io.
- Acesse o painel da NFe.io, clique em Conta, clique na aba Webhook localizado no submenu e em seguida Criar Webhook (https://app.nfe.io/hooks/new).
- Em Endereço (URL) informe a URL de retorno do seu WHMCS (http://www.seu_whmcs.com.br/modules/addons/nfeio/webhook.php) substituindo www.seu_whmcs.com.br pelo endereço do seu WHMCS e clique em Salvar.

Suporte: atendimento@sistema.digital
