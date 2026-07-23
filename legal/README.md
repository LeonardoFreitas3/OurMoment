# Páginas Legais — OurMoment

Textos base para as páginas legais da loja. **Não são aconselhamento jurídico.**

Escritos para um negócio de print-on-demand que vende a consumidores **na
Europa e nos Estados Unidos** em simultâneo, com produção pelo **Printful**
(que tem centros nos dois continentes e encaminha cada encomenda para o mais
próximo do cliente).

As diferenças entre regimes estão marcadas ao longo dos documentos com blocos
**Europe** e **United States**. O resto do texto aplica-se a toda a gente.

## Bloqueado até haver entidade registada

Estes campos identificam **quem vende** e não têm valor verdadeiro enquanto
não existir entidade:

- `[OurMoment, nome legal da empresa]`
- `[endereço]`
- `[número]` (NIF / EIN)
- `[jurisdição]` na cláusula de lei aplicável

Inventar qualquer um deles é declarar falsamente quem está a vender. Tanto a
lei europeia como a americana obrigam uma loja online a identificar o vendedor,
e o Stripe exige identificação fiscal antes de libertar pagamentos.

Em Portugal não é preciso constituir sociedade: abrir atividade nas Finanças
como empresário em nome individual dá nome legal, morada fiscal e NIF.

## Já se pode preencher

| Campo | Valor |
|---|---|
| `[data]` | data de publicação |
| `[contact@ourmoment.com]` | email de suporte real |
| `[o teu hosting]` | EasyWP (Namecheap) |
| `[Google Analytics, se usares]` | o que estiver instalado |
| `[2–5]` dias de produção | prazos reais do Printful |
| `[5–10]` dias de reembolso | Stripe demora 5–10 dias úteis |
| `[90]` / `[14]` / `[2]` | confirma que batem com o que fazes mesmo |

## Como publicar

Para cada ficheiro, no WordPress: **Páginas → Adicionar Nova**, cola o
conteúdo, e define o slug exacto indicado no topo. O footer do tema detecta as
páginas pelo slug e mostra os links automaticamente.

**Apaga o bloco `NOTA INTERNA` no fim de cada ficheiro antes de publicar.**

| Ficheiro | Slug | Título |
|---|---|---|
| `terms.md` | `terms` | Terms & Conditions |
| `privacy.md` | `privacy` | Privacy Policy |
| `returns.md` | `returns` | Returns & Refunds |

## Cookies

Não escrevas a política de cookies à mão — usa o **Complianz**, que já está
instalado. Ele gera o banner, faz o scan dos cookies reais (Stripe, Analytics,
Printful), gera a política e guarda o registo de consentimentos.

O Complianz está configurado em `region: us` / `optout`. **Como passaste a
vender também para a UE, isso tem de mudar:** a UE exige *opt-in* para cookies
não essenciais. O Complianz suporta as duas regiões em simultâneo e mostra o
banner certo conforme a localização do visitante — mas tens de ativar a Europa
no assistente.

O CSS do tema já estiliza o banner com as cores da marca.

## Antes de lançar

1. Abrir atividade / registar entidade
2. Decidir a jurisdição e preencher a cláusula de lei aplicável
3. Ativar a região Europa no Complianz
4. Confirmar prazos reais de produção e entrega do Printful
5. **Revisão por advogado** — vender aos EUA e à UE ao mesmo tempo significa
   cumprir os dois regimes; a excepção de livre resolução em produtos
   personalizados é a parte mais delicada deste modelo
