# -*- coding: utf-8 -*-
"""Gera o Plano de Marketing da OurMoment para os EUA em PDF (pt-PT, valores USD).

Porte do plano europeu para o mercado americano: sem IVA na origem, Stripe US
a 2,9% + $0.30, CPC americano, fornecedores Printify US, e um catálogo de
produtos completo (sem artigos de Natal).

Uso:  python build_marketing_pdf_us.py
"""

from reportlab.lib.pagesizes import A4
from reportlab.lib.units import mm
from reportlab.lib import colors
from reportlab.lib.enums import TA_CENTER, TA_RIGHT
from reportlab.platypus import (
    BaseDocTemplate, PageTemplate, Frame, Paragraph, Spacer, Table, TableStyle,
    HRFlowable, ListFlowable, ListItem, KeepTogether,
)
from reportlab.lib.styles import ParagraphStyle

# ── Paleta da marca ──
BROWN  = colors.HexColor("#B07E54")
TEXT   = colors.HexColor("#33291F")
MUTED  = colors.HexColor("#7C7065")
FAINT  = colors.HexColor("#A79C91")
RULE   = colors.HexColor("#E2D7CB")
SURF   = colors.HexColor("#FBF8F4")
GOOD   = colors.HexColor("#5F7F63")
BAD    = colors.HexColor("#A85A4A")
BADBG  = colors.HexColor("#F7EBE7")

SERIF   = "Times-Roman"
SERIF_I = "Times-Italic"
SANS    = "Helvetica"
SANS_B  = "Helvetica-Bold"

# Largura util da pagina: A4 menos as margens esquerda/direita (22 mm cada).
# Tabelas e caixas usam-na toda para alinharem com o corpo de texto.
CONTENT_W = 210 - 22 - 22  # 166 mm

styles = {
    "h1": ParagraphStyle("h1", fontName=SERIF_I, fontSize=24, leading=28,
                         textColor=TEXT, alignment=TA_CENTER, spaceAfter=4),
    "kicker": ParagraphStyle("kicker", fontName=SANS, fontSize=8, leading=12,
                             textColor=FAINT, alignment=TA_CENTER, spaceAfter=2),
    "h2": ParagraphStyle("h2", fontName=SERIF, fontSize=15, leading=19,
                         textColor=TEXT, spaceBefore=18, spaceAfter=3),
    "dek": ParagraphStyle("dek", fontName=SANS, fontSize=9.5, leading=14,
                          textColor=MUTED, spaceAfter=8),
    "h3": ParagraphStyle("h3", fontName=SANS_B, fontSize=8, leading=12,
                         textColor=BROWN, spaceBefore=10, spaceAfter=4),
    "body": ParagraphStyle("body", fontName=SANS, fontSize=9.5, leading=14.5,
                           textColor=TEXT, spaceAfter=7),
    "li": ParagraphStyle("li", fontName=SANS, fontSize=9.5, leading=14,
                         textColor=TEXT, spaceAfter=3),
    "note": ParagraphStyle("note", fontName=SANS, fontSize=9, leading=13.5,
                           textColor=TEXT, leftIndent=8, spaceAfter=4),
    "figure": ParagraphStyle("figure", fontName=SERIF, fontSize=30, leading=32,
                             textColor=BROWN, alignment=TA_CENTER),
    "figlabel": ParagraphStyle("figlabel", fontName=SANS, fontSize=8, leading=11,
                               textColor=MUTED, alignment=TA_CENTER, spaceBefore=4),
    "th": ParagraphStyle("th", fontName=SANS_B, fontSize=7.5, leading=10, textColor=MUTED),
    "td": ParagraphStyle("td", fontName=SANS, fontSize=8.5, leading=12, textColor=TEXT),
    # Variantes alinhadas a direita. O ALIGN do TableStyle so move a caixa da
    # celula; o texto dentro de um Paragraph segue o alinhamento do proprio
    # estilo, por isso as colunas numericas precisam destes.
    "thr": ParagraphStyle("thr", fontName=SANS_B, fontSize=7.5, leading=10,
                          textColor=MUTED, alignment=TA_RIGHT),
    "tdr": ParagraphStyle("tdr", fontName=SANS, fontSize=8.5, leading=12,
                          textColor=TEXT, alignment=TA_RIGHT),
    "phase": ParagraphStyle("phase", fontName=SANS_B, fontSize=7.5, leading=11,
                            textColor=BROWN, spaceAfter=2),
    "foot": ParagraphStyle("foot", fontName=SANS, fontSize=7.5, leading=11,
                           textColor=FAINT, alignment=TA_CENTER),
}


def P(t, s="body"):
    return Paragraph(t, styles[s])


def bullets(items):
    # value tem de ser o glifo do marcador. Passar um nome ("bullet") faz o
    # reportlab desenhar a propria palavra por cima do texto.
    return ListFlowable(
        [ListItem(P(t, "li"), leftIndent=10, value="•") for t in items],
        bulletType="bullet", start="•", leftIndent=12, bulletColor=BROWN,
    )


def headline(fig, label):
    t = Table([[P(fig, "figure")], [P(label, "figlabel")]],
              colWidths=[CONTENT_W * mm], hAlign="LEFT")
    t.setStyle(TableStyle([
        ("BOX", (0, 0), (-1, -1), 0.6, RULE),
        ("BACKGROUND", (0, 0), (-1, -1), SURF),
        ("TOPPADDING", (0, 0), (-1, -1), 12),
        ("BOTTOMPADDING", (0, 0), (-1, -1), 12),
    ]))
    return t


def note(html, kind="note"):
    bar = BROWN if kind == "note" else BAD
    bg = colors.white if kind == "note" else BADBG
    t = Table([[P(html, "note")]], colWidths=[CONTENT_W * mm], hAlign="LEFT")
    t.setStyle(TableStyle([
        ("LINEBEFORE", (0, 0), (0, -1), 2, bar),
        ("BACKGROUND", (0, 0), (-1, -1), bg),
        ("LEFTPADDING", (0, 0), (-1, -1), 10),
        ("RIGHTPADDING", (0, 0), (-1, -1), 10),
        ("TOPPADDING", (0, 0), (-1, -1), 8),
        ("BOTTOMPADDING", (0, 0), (-1, -1), 8),
    ]))
    return t


def table(headers, rows, aligns, widths):
    """Tabela a toda a largura util. `widths` sao proporcoes, reescaladas para
    CONTENT_W, para nunca ficarem mais estreitas do que o corpo de texto."""
    scale = CONTENT_W / float(sum(widths))
    widths = [w * scale for w in widths]

    def cell(text, col, header=False):
        right = aligns[col] == "RIGHT"
        return P(text, ("thr" if right else "th") if header else ("tdr" if right else "td"))

    data = [[cell(h, i, True) for i, h in enumerate(headers)]]
    for r in rows:
        data.append([cell(c, i) for i, c in enumerate(r)])
    t = Table(data, colWidths=[w * mm for w in widths], repeatRows=1, hAlign="LEFT")
    ts = [
        ("LINEBELOW", (0, 0), (-1, 0), 0.8, RULE),
        ("LINEBELOW", (0, 1), (-1, -2), 0.4, RULE),
        ("TOPPADDING", (0, 0), (-1, -1), 5),
        ("BOTTOMPADDING", (0, 0), (-1, -1), 5),
        ("LEFTPADDING", (0, 0), (-1, -1), 0),
        ("RIGHTPADDING", (0, 0), (-1, -1), 6),
        ("VALIGN", (0, 0), (-1, -1), "TOP"),
    ]
    for col, a in enumerate(aligns):
        ts.append(("ALIGN", (col, 0), (col, -1), a))
    t.setStyle(TableStyle(ts))
    return t


def titled(h3_text, tbl):
    """Um h3 e a sua tabela nunca se separam: sozinho no fundo da pagina, o
    titulo fica orfao e a tabela abre a pagina seguinte sem contexto."""
    return KeepTogether([P(h3_text, "h3"), tbl])


def phase(when, title, goal, items):
    """Uma fase do plano: cabeçalho + objetivo + bullets."""
    return [
        HRFlowable(width="100%", thickness=0.4, color=RULE, spaceBefore=10, spaceAfter=6),
        P(when, "phase"),
        Paragraph(title, ParagraphStyle("pt", fontName=SERIF, fontSize=11.5,
                                        leading=15, textColor=TEXT, spaceAfter=2)),
        Paragraph(goal, ParagraphStyle("pg", fontName=SANS, fontSize=7.5,
                                       leading=11, textColor=FAINT, spaceAfter=5)),
        bullets(items),
    ]




def build():
    story = []

    # ── Capa ──
    story.append(Spacer(1, 6 * mm))
    story.append(P("Plano de Marketing", "h1"))
    story.append(P("OURMOMENT · ESTADOS UNIDOS E EUROPA · 12 MESES", "kicker"))
    story.append(Spacer(1, 3 * mm))
    story.append(HRFlowable(width="100%", thickness=0.6, color=RULE, spaceAfter=6))

    # ── 0. Resumo ──
    story.append(P("O plano em cinco frases", "h2"))
    story.append(P("Se só leres esta página, já sabes o essencial.", "dek"))
    story.append(bullets([
        "<b>Vendemos presentes personalizados para casais</b> — quadros, canecas, almofadas — com as fotos, os nomes e a data de cada casal.",
        "<b>Não temos stock.</b> O Printful imprime e envia quando alguém compra. Só pagamos depois de vender.",
        "<b>Cada venda tem de deixar lucro suficiente para pagar o cliente seguinte.</b> É a regra que decide tudo o resto.",
        "<b>Nos primeiros meses não pagamos publicidade.</b> Vídeos, Pinterest e boca-a-boca. Publicidade só quando a loja já converte.",
        "<b>O quadro com moldura é o nosso produto principal</b> — é o que deixa mais lucro e onde somos mais baratos que a concorrência.",
    ]))

    # ── 1. Precos ──
    story.append(P("Os nossos preços, e porquê", "h2"))
    story.append(P("Um preço não se escolhe a olho. Tem de cumprir duas coisas ao mesmo "
                   "tempo: cobrir os custos com lucro que chegue, e caber no que o cliente "
                   "já viu noutro sítio.", "dek"))

    story.append(P("O QUE O PREÇO TEM DE PAGAR", "h3"))
    story.append(P("Exemplo com a caneca branca, que nos custa $5.95 no Printful:", "body"))
    story.append(table(
        ["", "Valor", "Para quem vai"],
        [
            ["Preço na loja", "$16.99", "—"],
            ["IVA (23%)", "-$3.18", "Estado. Só na Europa."],
            ["Custo de produção", "-$5.95", "Printful"],
            ["Taxa de pagamento", "-$0.79", "Stripe (2,9% + $0.30)"],
            ["<b>Fica para nós</b>", "<b>$7.07</b>", "<b>É deste dinheiro que vivemos</b>"],
        ],
        ["LEFT", "RIGHT", "LEFT"], [40, 22, 60]))
    story.append(note("<b>Atenção ao IVA.</b> Está dentro do preço que mostramos, mas não é nosso — "
                      "é entregue ao Estado. Nas vendas para os Estados Unidos não há IVA, por isso "
                      "o mesmo preço deixa-nos mais lucro lá do que na Europa."))

    story.append(P("A REGRA SIMPLES: PREÇO = CUSTO x 2,3", "h3"))
    story.append(P("É o múltiplo normal neste tipo de negócio. Abaixo de 2 vezes o custo não "
                   "sobra dinheiro para atrair clientes. Acima de 3 vezes ficamos caros e as "
                   "pessoas compram noutro lado.", "body"))

    story.append(titled("PREÇOS A APLICAR", table(
        ["Produto", "Custo", "Preço", "Lucro UE", "Lucro EUA"],
        [
            ["Caneca branca 11oz", "5.95", "16.99", "7.07", "10.25"],
            ["Caneca preta", "7.95", "19.99", "7.42", "11.16"],
            ["Caneca latte", "8.29", "19.99", "7.08", "10.82"],
            ["Enfeites acrílicos", "7.65", "19.99", "7.72", "11.46"],
            ["Vela em frasco", "13.21", "29.99", "10.00", "15.61"],
            ["Puzzle pequeno", "14.95", "32.99", "10.61", "16.78"],
            ["Almofada básica", "15.25", "34.99", "11.88", "18.43"],
            ["Almofada premium", "17.29", "39.99", "13.76", "21.24"],
            ["Puzzle grande", "24.43", "49.99", "14.46", "23.81"],
            ["<b>Quadro com moldura</b>", "<b>20.35</b>", "<b>49.99</b>", "<b>18.54</b>", "<b>27.89</b>"],
        ],
        ["LEFT", "RIGHT", "RIGHT", "RIGHT", "RIGHT"], [46, 16, 16, 18, 18])))

    story.append(note("<b>Porque é que o quadro é o produto mais importante.</b> Deixa-nos $18.54 "
                      "de lucro na Europa — o dobro de uma caneca — e mesmo assim custa $49.99 "
                      "quando a Shutterfly, o maior concorrente, cobra $84.99 pelo equivalente. "
                      "É o único produto onde somos ao mesmo tempo mais baratos e mais rentáveis."))
    story.append(Spacer(1, 4))
    story.append(note("<b>A caneca serve para outra coisa.</b> Com $7.07 de lucro não sustenta o "
                      "negócio, mas é barata o suficiente para alguém comprar pela primeira vez. "
                      "Primeiro conquista-se o cliente com a caneca; depois vende-se-lhe o quadro."))

    story.append(P("COMPARAÇÃO COM A CONCORRÊNCIA", "h3"))
    story.append(table(
        ["Produto", "Nós", "Concorrência", "Situação"],
        [
            ["Quadro com moldura", "$49.99", "Shutterfly $84.99", "Muito mais baratos"],
            ["Caneca", "$16.99", "Shutterfly $10.99", "Um pouco mais caros"],
            ["Almofada", "$34.99", "PersonalizationMall $19.99", "Mais caros"],
            ["Puzzle", "$32.99", "Shutterfly $24.99", "Mais caros"],
        ],
        ["LEFT", "RIGHT", "LEFT", "LEFT"], [34, 16, 40, 30]))
    story.append(P("Onde estamos mais caros, só se justifica se as nossas fotos e o nosso design "
                   "forem visivelmente melhores. Se não forem, mais vale baixar o preço.", "body"))

    # ── 2. A regra ──
    story.append(P("A regra que decide tudo", "h2"))
    story.append(P("Existe uma só conta que determina se o negócio funciona.", "dek"))
    story.append(headline("Lucro por venda &gt; Custo de trazer o cliente",
                          "SE ISTO FOR AO CONTRÁRIO, VENDEMOS E PERDEMOS DINHEIRO"))
    story.append(Spacer(1, 8))
    story.append(P("Se uma venda nos deixa $10 de lucro, podemos gastar até $10 em publicidade "
                   "para a conseguir. Gastar $12 significa perder $2 — mesmo tendo vendido.", "body"))
    story.append(P("Com os preços da tabela acima, ficamos com cerca de <b>$11 de lucro médio na "
                   "Europa</b> e <b>$17 nos Estados Unidos</b>. Isso permite pagar $6 a $8 por "
                   "cliente e ainda sobrar. É esse espaço que torna a publicidade possível.", "body"))
    story.append(note("<b>Porque é que os preços de agora não servem.</b> Os produtos estão na loja "
                      "com o preço de custo do Printful. Uma caneca a $5.95 deixa-nos <b>menos "
                      "$1.59</b> — perdemos dinheiro em cada venda. Nenhuma campanha resolve isso; "
                      "só piora, porque quanto mais vendemos mais perdemos.", "warn"))

    # ── 3. Porque nao anunciar ja ──
    story.append(P("Porque não vamos pagar publicidade já", "h2"))
    story.append(P("É a pergunta que toda a gente faz primeiro. A resposta é aritmética, "
                   "não opinião.", "dek"))
    story.append(P("Nos Estados Unidos, cada clique num anúncio custa entre $0.50 e $1.20. "
                   "Se de cada 100 pessoas que clicam só 2 comprarem, cada venda custou-nos "
                   "50 cliques — ou seja, $25 a $60 em publicidade para um produto que nos "
                   "deixa $17.", "body"))
    story.append(table(
        ["Custo por clique", "Se 2 em 100 comprarem", "Se 5 em 100 comprarem"],
        [
            ["$0.50", "custa $25 por venda", "custa $10 por venda"],
            ["$0.80", "custa $40 por venda", "custa $16 por venda"],
            ["$1.20", "custa $60 por venda", "custa $24 por venda"],
        ],
        ["LEFT", "LEFT", "LEFT"], [30, 38, 38]))
    story.append(P("Uma loja nova, sem avaliações e sem fotos reais, costuma ficar nos "
                   "<b>1 a 2 em cada 100</b>. Quase toda a tabela dá prejuízo. Não é falta de "
                   "jeito para fazer anúncios — é o preço do clique contra o nosso lucro.", "body"))
    story.append(note("<b>Há uma exceção, e é importante.</b> Mostrar anúncios a quem <i>já visitou "
                      "a loja</i> e não comprou funciona muito melhor: dessas pessoas compram 8 a "
                      "12 em cada 100, porque já nos conhecem. Aí o custo por venda cai para uns "
                      "$6 e passa a dar lucro. É por isso que começamos por aí, e não por "
                      "anúncios a estranhos."))

    # ── 4. Fases ──
    story.append(P("O que fazer, mês a mês", "h2"))
    story.append(P("Quatro fases. Cada uma tem um objetivo e uma condição para avançar. "
                   "Não se passa à seguinte sem cumprir a condição.", "dek"))

    story += phase("MESES 1 E 2", "Provar que o produto é bom",
                   "OBJETIVO: AS PRIMEIRAS 10 VENDAS · PUBLICIDADE: ZERO", [
        "<b>Encomendar amostras</b> de cada produto para nós próprios e fotografá-las em casa, com luz de janela. As fotos do Printful são genéricas e não emocionam ninguém.",
        "<b>Vender a família e amigos.</b> Não é batota — são as primeiras 10 avaliações e as primeiras fotos de clientes reais.",
        "<b>Abrir TikTok e Instagram</b> e publicar todos os dias. Não é para vender: é para mostrar como se faz.",
        "<b>Abrir Pinterest</b> como conta de empresa.",
        "<b>Instalar o Google Analytics</b> para sabermos quantas pessoas visitam e o que fazem.",
    ])

    story += phase("MESES 3 A 5", "Fazer conteúdo que traga visitas sozinho",
                   "OBJETIVO: 50 VENDAS · PUBLICIDADE: ~$40/MÊS", [
        "<b>Vídeos 5 vezes por semana</b> no TikTok e Instagram. O formato que funciona acima de todos: a cara de quem recebe o presente.",
        "<b>Pinterest, 3 publicações por dia.</b> As pessoas procuram presentes lá com meses de antecedência, e uma boa imagem traz visitas durante anos.",
        "<b>Oferecer produtos a criadores pequenos</b> (5 a 30 mil seguidores) em troca de um vídeo. Pagamos em produto, não em dinheiro.",
        "<b>Começar a lista de emails</b> com 10% de desconto na primeira compra.",
        "<b>Email automático de carrinho abandonado</b> — quem põe no carrinho e não compra recebe um lembrete. É a única automação que se paga sozinha.",
        "<b>Pedir avaliação</b> por email 10 dias depois da entrega.",
    ])

    story += phase("MESES 6 A 8", "Fazer cada cliente gastar mais",
                   "OBJETIVO: SUBIR A COMPRA MÉDIA · PUBLICIDADE: ~$180/MÊS", [
        "<b>Conjuntos.</b> 'Quadro + caneca a condizer' com 10% de desconto. Vender dois artigos à mesma pessoa custa-nos muito menos do que arranjar outro cliente.",
        "<b>Embrulho de oferta por $5.90.</b> Custa-nos cêntimos e reforça exatamente o que a marca promete.",
        "<b>Primeira publicidade a sério:</b> anúncios só a quem já visitou a loja.",
        "<b>Só agora</b> testar anúncios a pessoas novas, e apenas se a loja já converter bem.",
    ])

    story += phase("MESES 9 A 12", "Aumentar o que já está a resultar",
                   "OBJETIVO: LUCRO CONSTANTE · PUBLICIDADE: 30% DO LUCRO", [
        "<b>Subir o investimento em publicidade 20% por semana</b>, nunca mais do que isso.",
        "<b>O Google começa a trazer visitas</b> sem pagarmos nada, fruto do trabalho dos meses anteriores.",
        "<b>Parcerias com fotógrafos de casamento</b> — falam exatamente com o nosso cliente, e só pagamos quando há venda.",
    ])

    # ── 5. Dinheiro ──
    story.append(P("Quanto vamos gastar", "h2"))
    story.append(P("Quase tudo o que se gasta sai do lucro das vendas anteriores, não do bolso.", "dek"))

    story.append(titled("CUSTOS FIXOS DA LOJA", table(
        ["O quê", "Quanto"],
        [
            ["Alojamento do site", "$4/mês"],
            ["Domínio ourmoment.shop", "$1/mês"],
            ["Email da marca", "$1/mês"],
            ["Printful, WooCommerce, Complianz", "grátis"],
            ["<b>Total</b>", "<b>$6/mês</b>"],
        ],
        ["LEFT", "RIGHT"], [70, 30])))
    story.append(P("Com $6 por mês, basta <b>uma venda</b> para cobrir os custos da loja. "
                   "Todo o resto do lucro fica disponível para crescer.", "body"))

    story.append(titled("PUBLICIDADE, MÊS A MÊS", table(
        ["Meses", "Em quê", "Por mês", "Acumulado"],
        [
            ["1-2", "Nada. Só conteúdo e amostras.", "$0", "$0"],
            ["3-5", "Produtos oferecidos a criadores", "$40", "$120"],
            ["6-8", "Anúncios a quem já nos visitou", "$180", "$660"],
            ["9-12", "Aumentar o que resulta", "$250-400", "$1.660-2.260"],
        ],
        ["LEFT", "LEFT", "RIGHT", "RIGHT"], [12, 52, 18, 22])))
    story.append(note("<b>A regra dos 30%.</b> A partir do momento em que há vendas, gastamos 30% "
                      "do lucro em marketing e guardamos os outros 70%. Nunca se gasta dinheiro "
                      "que a loja ainda não ganhou."))

    # ── 6. Calendario ──
    story.append(P("As datas que decidem o ano", "h2"))
    story.append(P("Presentes para casais vende-se por picos. Metade do ano joga-se em "
                   "seis semanas.", "dek"))
    story.append(table(
        ["Quando", "O quê", "Quando começar a preparar"],
        [
            ["14 de fevereiro", "Dia dos Namorados", "5 de janeiro"],
            ["Maio e junho", "Dia da Mãe e do Pai", "abril"],
            ["Maio a outubro", "Época de casamentos", "março"],
            ["Fim de novembro", "Black Friday", "outubro"],
            ["<b>Dezembro</b>", "<b>Natal</b>", "<b>outubro</b>"],
        ],
        ["LEFT", "LEFT", "LEFT"], [26, 40, 34]))
    story.append(note("<b>A data-limite de encomenda é sagrada.</b> Entre produzir e entregar vão "
                      "6 a 13 dias úteis. Um presente de Natal que chega a 27 de dezembro não é "
                      "um atraso — é uma devolução, uma avaliação de uma estrela, e um cliente "
                      "que nunca mais volta. A partir de 1 de dezembro, a data-limite tem de "
                      "estar no topo do site."))

    # ── 7. KPIs ──
    story.append(P("Como saber se está a resultar", "h2"))
    story.append(P("Cinco números. Se estes forem bem, está tudo bem.", "dek"))
    story.append(table(
        ["O quê", "Onde queremos chegar", "Porque importa"],
        [
            ["Quantos visitantes compram", "mais de 1,5 em 100", "Abaixo disto, publicidade dá prejuízo"],
            ["Quanto gasta cada cliente", "$55 por compra", "Quanto mais alto, mais podemos investir"],
            ["Custo de trazer um cliente", "menos de $9", "Tem de ficar abaixo do lucro por venda"],
            ["Avaliações", "mais de 30", "Com avaliações, vende-se para o dobro"],
            ["Lista de emails", "500 pessoas", "Vender a quem já comprou é quase de graça"],
        ],
        ["LEFT", "LEFT", "LEFT"], [32, 26, 42]))
    story.append(note("<b>A coisa mais barata que podemos fazer não é publicidade.</b> Se de cada "
                      "100 visitantes passarem a comprar 2 em vez de 1, vendemos o dobro sem "
                      "gastar um cêntimo. Antes de pagar mais visitas, vale sempre mais a pena "
                      "melhorar a loja: fotos reais, avaliações à vista, prazo de entrega claro."))

    # ── 8. Amanha ──
    story.append(P("O que fazer a seguir", "h2"))
    story.append(P("Por esta ordem, e nada mais até estarem feitas.", "dek"))
    story.append(bullets([
        "<b>Corrigir os preços</b> na loja, com a tabela deste plano. Neste momento perdemos dinheiro em cada venda.",
        "<b>Apagar os produtos repetidos</b> e os que não são para casais. Ficar com dez, um de cada.",
        "<b>Encomendar as amostras</b> e fotografá-las. Não se vende o que nunca se teve na mão.",
        "<b>Abrir TikTok e Pinterest</b> e publicar o primeiro vídeo. Ninguém vai ver. Publicar na mesma.",
        "<b>Vender à primeira pessoa que conhecemos</b> e pedir uma avaliação com foto.",
    ]))

    # ── 9. Dicionario ──
    story.append(P("Dicionário", "h2"))
    story.append(P("Os termos que aparecem neste plano e em qualquer conversa sobre "
                   "marketing, em português simples.", "dek"))
    story.append(table(
        ["Termo", "O que quer dizer"],
        [
            ["Margem", "O que sobra de uma venda depois de pagar tudo. É o dinheiro real."],
            ["CAC", "Quanto custa trazer um cliente. Se for maior que a margem, perdemos dinheiro."],
            ["Taxa de conversão", "De cada 100 visitantes, quantos compram. Uma loja normal fica nos 1 a 3."],
            ["AOV", "Quanto gasta em média cada cliente numa compra."],
            ["Print-on-demand", "Só se imprime depois de alguém comprar. Não temos stock nem dinheiro parado."],
            ["Retargeting", "Mostrar anúncios a quem já visitou a loja. Muito mais barato que anunciar a estranhos."],
            ["SEO", "Aparecer no Google sem pagar. Demora meses, mas depois é de graça."],
            ["Carrinho abandonado", "Quem põe no carrinho e não compra. Um email costuma recuperar parte."],
            ["Seeding", "Oferecer produto a criadores em troca de um vídeo. Paga-se em produto, não em dinheiro."],
            ["CPC", "Quanto custa cada clique num anúncio."],
        ],
        ["LEFT", "LEFT"], [24, 76]))

    story.append(Spacer(1, 10))
    story.append(HRFlowable(width="100%", thickness=0.5, color=RULE, spaceAfter=6))
    story.append(P("Custos de produção reais do Printful. Stripe a 2,9% + $0.30. IVA a 23% "
                   "incluído nos preços em euros; vendas para fora da União Europeia são "
                   "isentas. Prazos de entrega por confirmar com a primeira encomenda de "
                   "amostra. Não é aconselhamento financeiro nem fiscal.", "foot"))

    # ── Render ──
    doc = BaseDocTemplate(
        "marketing-plan-us.pdf", pagesize=A4,
        leftMargin=22 * mm, rightMargin=22 * mm, topMargin=18 * mm, bottomMargin=16 * mm,
        title="Plano de Marketing — OurMoment", author="OurMoment",
    )
    frame = Frame(doc.leftMargin, doc.bottomMargin, doc.width, doc.height, id="main")

    def footer(canvas, d):
        canvas.saveState()
        canvas.setFont(SANS, 7)
        canvas.setFillColor(FAINT)
        canvas.drawCentredString(A4[0] / 2, 9 * mm,
                                 f"OurMoment · Plano de Marketing · {d.page}")
        canvas.restoreState()

    doc.addPageTemplates([PageTemplate(id="main", frames=[frame], onPage=footer)])
    doc.build(story)
    print("PDF criado: marketing-plan-us.pdf")


if __name__ == "__main__":
    build()
