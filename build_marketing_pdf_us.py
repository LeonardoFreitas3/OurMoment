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

    # ── Masthead ──
    story.append(Spacer(1, 6 * mm))
    story.append(P("Plano de Marketing", "h1"))
    story.append(P("OURMOMENT · ESTADOS UNIDOS · 12 MESES · USD", "kicker"))
    story.append(Spacer(1, 3 * mm))
    story.append(HRFlowable(width="100%", thickness=0.6, color=RULE, spaceAfter=6))

    # ── 1. O número ──
    story.append(P("O número que decide tudo o resto", "h2"))
    story.append(P("Antes de escolher canais, é preciso saber quanto podes pagar por cliente. "
                   "Tudo o que vem depois neste plano decorre daqui.", "dek"))
    story.append(headline("$14.59", "MARGEM DE CONTRIBUIÇÃO POR ENCOMENDA"))
    story.append(Spacer(1, 8))
    story.append(P("Numa encomenda média de <b>$38.73</b> — 1,3 artigos, mix de canecas e quadros — "
                   "sobram <b>$14.59</b> depois do custo de produção do Printify US, os portes domésticos "
                   "e a comissão do Stripe. <b>Nos EUA não há IVA na origem</b>: o sales tax é somado por "
                   "cima e cobrado à parte, por isso ficas com uma fatia maior da venda do que na Europa.", "body"))
    story.append(P("Esse é o teto absoluto do que podes gastar para conquistar um cliente. Se gastares mais "
                   "de $14.59 a angariar uma venda, estás a pagar para vender.", "body"))
    story.append(note("<b>Percentagens não pagam anúncios — dólares pagam.</b> A caneca tem a melhor "
                      "<i>percentagem</i> de margem, mas o canvas e o cobertor dão-te três a quatro vezes "
                      "mais dólares. Quando desenhares bundles, otimiza para dólares por encomenda. "
                      "A margem já desconta o Stripe US a 2,9% + $0.30 por venda."))

    # ── 2. O catálogo ──
    story.append(P("O catálogo completo que a loja deve ter", "h2"))
    story.append(P("Nove produtos, agrupados pelo papel que cada um cumpre. Sem artigos de Natal — "
                   "esses entram à parte, em setembro, como linha sazonal.", "dek"))

    story.append(titled("HERÓIS — SÃO ESTES QUE SUSTENTAM O TRÁFEGO PAGO", table(
        ["Produto", "Papel na loja", "Retail", "Margem"],
        [
            ["Hooded Sherpa Fleece Blanket", "O de maior margem em dólares. Já está na loja.", "$89.00", "$38.12"],
            ["Stretched Canvas 16x20", "Presente de parede de valor alto", "$59.99", "$17.95"],
            ["Framed Vertical Poster (matte)", "Produto-assinatura: combina com o design do site", "$39.99", "$14.53"],
        ],
        ["LEFT", "LEFT", "RIGHT", "RIGHT"], [42, 62, 20, 22])))

    story.append(titled("VOLUME — PORTA DE ENTRADA E COMPLEMENTO DE BUNDLE", table(
        ["Produto", "Papel na loja", "Retail", "Margem"],
        [
            ["Ceramic Mug 11oz", "Entrada barata. Complemento, nunca herói.", "$22.99", "$9.02"],
            ["Matte Poster (sem moldura)", "Versão acessível do quadro", "$24.99", "$9.97"],
        ],
        ["LEFT", "LEFT", "RIGHT", "RIGHT"], [42, 62, 20, 22])))

    story.append(titled("PARES E UPSELL — SOBEM O AOV SEM TRÁFEGO NOVO", table(
        ["Produto", "Papel na loja", "Retail", "Margem"],
        [
            ["Throw Pillow 18x18", "Clássico de aniversário de namoro", "$44.99", "$16.89"],
            ["Acrylic Photo Block", "Valor percebido alto, custo de portes baixo", "$39.99", "$16.03"],
            ["Stainless Tumbler 20oz", "Vende-se aos pares por natureza", "$34.99", "$14.18"],
        ],
        ["LEFT", "LEFT", "RIGHT", "RIGHT"], [42, 62, 20, 22])))

    story.append(titled("OCASIÃO — ÂNGULO DE EXPERIÊNCIA, NÃO DE DECORAÇÃO", table(
        ["Produto", "Papel na loja", "Retail", "Margem"],
        [
            ["Photo Puzzle 500pc", "Ângulo 'date night'. Conteúdo de vídeo fácil.", "$34.99", "$12.68"],
        ],
        ["LEFT", "LEFT", "RIGHT", "RIGHT"], [42, 62, 20, 22])))

    story.append(note("<b>Custos a confirmar no Printify US.</b> As margens acima assumem custos de produção "
                      "e portes domésticos típicos. Antes de fixares preços, abre cada produto no Printify, "
                      "filtra os <i>print providers</i> por <b>United States</b>, e mete os custos reais na conta. "
                      "A disponibilidade de fornecedor varia de produto para produto."))
    story.append(Spacer(1, 4))
    story.append(note("<b>Porque o cobertor é o produto mais importante da lista.</b> Com $38 de margem, "
                      "aguenta sozinho um custo de aquisição que a caneca nunca aguentaria. "
                      "É ele que torna o tráfego pago viável nos EUA — lidera com ele, não com a caneca."))
    story.append(Spacer(1, 4))
    story.append(note("<b>O Customily não é o filtro.</b> Suporta o catálogo Printify inteiro, por isso "
                      "qualquer destes produtos é personalizável. Os filtros reais são ter fornecedor nos "
                      "EUA e encaixar na marca."))

    # ── 3. Porque não anúncios frios ──
    story.append(P("Porque os anúncios frios quase não funcionam ao início", "h2"))
    story.append(P("O instinto é abrir o Meta Ads Manager e pôr $10/dia. Nos EUA, com este AOV e o CPC "
                   "americano, isso é matemática perdedora — ainda mais dura do que na Europa.", "dek"))
    story.append(P("O CPC de retail nos EUA anda entre <b>$0.50 e $1.20+</b>. Com a margem de $14.59, "
                   "eis o lucro ou prejuízo por venda:", "body"))
    def loss(v):
        return f'<font color="#{BAD.hexval()[2:]}">-${v} prejuízo</font>'

    def gain(v):
        return f'<font color="#{GOOD.hexval()[2:]}">+${v} lucro</font>'

    story.append(table(
        ["CPC", "Conversão 2%", "Conversão 3%", "Conversão 5%"],
        [
            ["$0.50", loss("10.41"), loss("2.08"), gain("4.59")],
            ["$0.80", loss("25.41"), loss("12.08"), loss("1.41")],
            ["$1.20", loss("45.41"), loss("25.41"), loss("9.41")],
        ],
        ["LEFT", "RIGHT", "RIGHT", "RIGHT"], [24, 40, 40, 42]))
    story.append(P("Uma loja nova, sem provas sociais e sem tráfego para o algoritmo otimizar, converte perto "
                   "de <b>1–2%</b> em tráfego frio. Quase toda a tabela é vermelha. Não é falta de habilidade "
                   "a fazer anúncios — é o AOV baixo contra o CPC alto.", "body"))
    story.append(note("<b>O tráfego quente muda tudo.</b> Retargeting a quem já te visitou converte perto de "
                      "8–12%, não 1–2%. A 10% de conversão e $0.60 de CPC, o custo por venda é <b>$6</b> "
                      "— lucro de $8.59. Por isso, nos EUA, o retargeting é o <i>único</i> canal pago que faz "
                      "sentido antes de teres tudo provado."))
    story.append(Spacer(1, 4))
    story.append(note("<b>A armadilha do desespero.</b> Quando as vendas não aparecem no mês 2, a tentação é "
                      "abrir anúncios frios. É o pior momento: pagas o preço máximo por cliente na altura em "
                      "que tens menos dinheiro. Resiste até teres 30 avaliações, AOV acima de $50 e conversão "
                      "acima de 1,5%.", "warn"))

    # ── 4. Fases ──
    story.append(P("O plano, em quatro fases", "h2"))
    story.append(P("Cada fase tem um objetivo único e um critério de saída. Não avances sem cumprir o "
                   "critério — é assim que se evita queimar dinheiro.", "dek"))

    story += phase("MÊS 1–2", "Fundações e prova de produto",
                   "OBJETIVO: AS PRIMEIRAS 10 VENDAS · ANÚNCIOS: $0 · AMOSTRAS: ~$100", [
        "<b>Encomenda amostras</b> ao fornecedor Printify US que vais usar. Fotografa-as com luz natural. Os mockups do Printify não vendem emoção.",
        "<b>Vende aos círculos próximos.</b> São as tuas primeiras 10 avaliações e as primeiras fotos reais.",
        "<b>Abre TikTok e Instagram</b> em inglês, para público americano. Publica 1x/dia. Não vendas — mostra o processo.",
        "<b>Pinterest como conta de empresa</b>, com Rich Pins ativos.",
        "<b>Instala o Google Analytics e o Meta Pixel.</b> Ainda não anuncias, mas o pixel precisa de meses a aprender.",
        "<b>Configura o sales tax</b> no WooCommerce. Ao início só recolhes no teu estado de nexus.",
    ])

    story += phase("MÊS 3–5", "Motor de conteúdo orgânico",
                   "OBJETIVO: 50 VENDAS · CONVERSÃO >1,5% · ANÚNCIOS: ~$40/MÊS", [
        "<b>TikTok e Reels, 5x/semana.</b> Um formato acima de todos: a reação de quem recebe o presente.",
        "<b>Pinterest, 3 pins/dia.</b> Os americanos pesquisam presentes lá com meses de antecedência.",
        "<b>Semeia com micro-influencers.</b> Contas de casais com 5–30 mil seguidores. Pagas em produto, sem cachê.",
        "<b>Começa a lista de email</b> com 10% de desconto na primeira compra.",
        "<b>Carrinho abandonado:</b> três emails automáticos. A única automação que se paga sozinha.",
        "<b>Pede avaliação</b> por email 10 dias após a entrega, com foto.",
    ])

    story += phase("MÊS 6–8", "Subir o valor por encomenda",
                   "OBJETIVO: AOV DE $39 PARA $55 · ANÚNCIOS: ~$180/MÊS", [
        "<b>Bundles.</b> 'Cobertor + caneca a condizer' com 10% de desconto. É a alavanca nº1 nos EUA.",
        "<b>Embrulho de oferta a $5.90.</b> Custa cêntimos, é margem quase pura, reforça a promessa da marca.",
        "<b>Retargeting</b> a quem visitou e não comprou. O teu primeiro e melhor dólar de publicidade.",
        "<b>Só agora testa anúncios frios</b>, e só se a conversão passou 1,5% <i>e</i> o AOV passou $50. $5/dia, lookalike dos compradores. Mata o que passe $9 de CAC.",
    ])

    story += phase("MÊS 9–12", "Escalar o que já funciona",
                   "OBJETIVO: RENTABILIDADE SUSTENTADA · ANÚNCIOS: 30% DA MARGEM", [
        "<b>Sobe os anúncios 20% por semana</b>, nunca mais. Saltos maiores reiniciam a aprendizagem do algoritmo.",
        "<b>SEO começa a pagar.</b> Os artigos do mês 3 posicionam-se agora. Tráfego orgânico tem CAC zero.",
        "<b>Programa de afiliados</b> com fotógrafos de casamento e wedding planners americanos.",
        "<b>Expande para o Canadá</b> — os mesmos fornecedores US enviam para lá, sem tradução de criativos.",
    ])

    # ── 5. Canais ──
    story.append(P("Canais, por ordem de retorno", "h2"))
    story.append(P("Com orçamento inicial baixo, a ordem importa mais do que a execução.", "dek"))
    story.append(table(
        ["Canal", "Custo", "Retorno", "Porquê para ti"],
        [
            ["TikTok / Reels", "Grátis", "Alto, lento", "O produto tem um momento de reação filmável"],
            ["Pinterest", "Grátis", "Alto, composto", "É um motor de busca. Planeiam presentes lá."],
            ["Email", "Grátis até 250", "Muito alto", "Carrinho abandonado. Tu és dono da lista."],
            ["Retargeting", "$3–5/dia", "Alto", "Conversão 8–12%. O único pago que dá lucro cedo."],
            ["Seeding a influencers", "$8–18/peça", "Médio, variável", "Pagas em produto. Gera conteúdo reutilizável."],
            ["SEO", "Grátis", "Alto, muito lento", "6 meses até dar fruto. Planta agora."],
            ["Anúncios frios", "$10+/dia", "Negativo, ao início", "Só com conversão >1,5% e AOV >$50"],
        ],
        ["LEFT", "LEFT", "LEFT", "LEFT"], [32, 24, 28, 62]))

    # ── 6. Calendário ──
    story.append(P("O calendário manda mais do que tu", "h2"))
    story.append(P("Presentes para casais é um negócio de picos. Metade do ano decide-se em seis semanas.", "dek"))
    story.append(table(
        ["Quando", "Ocasião", "Preparar"],
        [
            ["1 Jan", "Resoluções, 'new year together'", "Conteúdo de retrospetiva do ano"],
            ["14 Fev", "Valentine's Day", "Começa a 5 Jan. Fecha encomendas ~2 Fev."],
            ["Mai / Jun", "Mother's &amp; Father's Day", "Ângulo de família, não só de casal"],
            ["Mai–Out", "Wedding season (EUA)", "Afiliados com fotógrafos e wedding planners"],
            ["Fim Nov", "Black Friday / Cyber Monday", "Desconta bundles, nunca produtos isolados"],
            ["Dez", "Natal", "Até 40% do ano. Prazo de encomenda ~12 Dez."],
        ],
        ["LEFT", "LEFT", "LEFT"], [24, 52, 70]))
    story.append(note("<b>O prazo de encomenda é sagrado.</b> Produção mais entrega doméstica nos EUA são "
                      "4 a 8 dias úteis. Um presente que chega a 27 de dezembro não é um atraso — é uma "
                      "devolução, uma avaliação de uma estrela, e um cliente que nunca mais volta."))

    # ── 7. Custos ──
    story.append(P("Quanto vais pagar", "h2"))
    story.append(P("Tudo em dólares por mês, salvo indicação.", "dek"))
    story.append(titled("CUSTOS FIXOS, A PARTIR DO PRIMEIRO DIA", table(
        ["Item", "Plano", "$/mês"],
        [
            ["Alojamento", "EasyWP", "4.00"],
            ["Domínio", "~$12/ano", "1.00"],
            ["Email profissional", "Zoho Mail, 1 utilizador", "1.00"],
            ["Printify", "Free — paga-se por venda", "0.00"],
            ["WooCommerce, Yoast, Complianz", "Gratuitos", "0.00"],
            ["Stripe", "2,9% + $0.30 por venda", "0.00"],
            ["<b>Total fixo</b>", "", "<b>$6.00</b>"],
        ],
        ["LEFT", "LEFT", "RIGHT"], [50, 66, 30])))

    story.append(titled("INVESTIMENTO INICIAL, UMA SÓ VEZ", table(
        ["Item", "Notas", "$"],
        [
            ["Amostras dos produtos", "Uma de cada, do fornecedor US. Inegociável.", "80–120"],
            ["Registo + sales tax permit", "LLC opcional; permit no teu estado", "50–300"],
            ["Fotografia", "Telemóvel e luz de janela", "0"],
            ["<b>Total de arranque</b>", "", "<b>$130–420</b>"],
        ],
        ["LEFT", "LEFT", "RIGHT"], [50, 66, 30])))

    # ── 8. Orçamento ──
    story.append(P("O orçamento de marketing, mês a mês", "h2"))
    story.append(P("Cada dólar sai da margem que a venda anterior gerou.", "dek"))
    story.append(table(
        ["Mês", "Onde vai o dinheiro", "$/mês", "Acumulado"],
        [
            ["1–2", "Nada em ads. Orgânico e amostras.", "0", "0"],
            ["3–5", "Seeding: 2 produtos/mês a influencers", "40", "120"],
            ["6–8", "Retargeting a $5/dia + seeding", "180", "660"],
            ["9–12", "Escala retargeting + lookalike, se CAC < $9", "250–400", "1,660–2,260"],
            ["<b>Ano 1</b>", "", "", "<b>$1,650–2,250</b>"],
        ],
        ["LEFT", "LEFT", "RIGHT", "RIGHT"], [18, 76, 26, 26]))
    story.append(note("<b>A regra dos 30%.</b> A partir do momento em que vendes, reinveste 30% da margem em "
                      "marketing e guarda o resto. Nunca gastes dinheiro que a loja ainda não ganhou. "
                      "Um negócio POD não tem stock parado — a única forma de ires à falência é comprares "
                      "tráfego mais caro do que o que ele te devolve."))

    # ── 9. KPIs ──
    story.append(P("Quando é seguro acelerar", "h2"))
    story.append(P("Não avances por sentires que está na altura. Avança quando estes números o disserem.", "dek"))
    story.append(table(
        ["Métrica", "Agora", "Meta", "Porquê importa"],
        [
            ["Taxa de conversão", "—", ">1,5%", "Abaixo disto, os anúncios frios dão prejuízo"],
            ["Encomenda média", "$39", "$55", "A alavanca nº1 nos EUA"],
            ["CAC", "$0", "<$9", "Teto absoluto: $14.59"],
            ["Avaliações", "0", "30+", "A conversão duplica entre 0 e 30"],
            ["Lista de email", "0", "500", "Deve valer 25% da receita"],
            ["Compras repetidas", "—", ">15%", "Aniversários repetem-se todos os anos"],
        ],
        ["LEFT", "RIGHT", "RIGHT", "LEFT"], [34, 18, 18, 76]))
    story.append(note("<b>A alavanca mais barata não é o marketing.</b> Subir a conversão de 1% para 2% duplica "
                      "as vendas sem gastares um cêntimo. Antes de pagares por mais tráfego, arranca mais valor "
                      "do que já tens: fotos reais, avaliações visíveis, prazo claro, checkout curto e um "
                      "bundle que sobe o AOV."))

    # ── 10. Amanhã ──
    story.append(P("O que fazer amanhã de manhã", "h2"))
    story.append(bullets([
        "<b>Encomenda as amostras</b> ao fornecedor Printify US — começa pelo cobertor e pelo quadro. Não podes vender o que nunca tiveste na mão.",
        "<b>Abre TikTok e Pinterest em inglês</b> e publica o primeiro vídeo do desembrulhar. Ninguém vai ver. Publica na mesma.",
        "<b>Vende à primeira pessoa que conheces</b> e pede uma avaliação com foto. Vale mais que os primeiros $100 de anúncios.",
    ]))

    story.append(Spacer(1, 10))
    story.append(HRFlowable(width="100%", thickness=0.5, color=RULE, spaceAfter=6))
    story.append(P("Números com Stripe US a 2,9% + $0.30, sem IVA (sales tax cobrado à parte), fornecedores "
                   "Printify US e portes domésticos. Custos de produção e portes são estimativas — confirma-os "
                   "no Printify antes de fixar preços. Não é aconselhamento financeiro nem fiscal.", "foot"))

    # ── Render ──
    doc = BaseDocTemplate(
        "marketing-plan-us.pdf", pagesize=A4,
        leftMargin=22 * mm, rightMargin=22 * mm, topMargin=18 * mm, bottomMargin=16 * mm,
        title="Plano de Marketing EUA — OurMoment", author="OurMoment",
    )
    frame = Frame(doc.leftMargin, doc.bottomMargin, doc.width, doc.height, id="main")

    def footer(canvas, d):
        canvas.saveState()
        canvas.setFont(SANS, 7)
        canvas.setFillColor(FAINT)
        canvas.drawCentredString(A4[0] / 2, 9 * mm,
                                 f"OurMoment · Plano de Marketing EUA · {d.page}")
        canvas.restoreState()

    doc.addPageTemplates([PageTemplate(id="main", frames=[frame], onPage=footer)])
    doc.build(story)
    print("PDF criado: marketing-plan-us.pdf")


if __name__ == "__main__":
    build()
