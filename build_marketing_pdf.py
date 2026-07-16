# -*- coding: utf-8 -*-
"""Gera o Plano de Marketing da OurMoment em PDF (pt-PT), revisto e enxuto."""

from reportlab.lib.pagesizes import A4
from reportlab.lib.units import mm
from reportlab.lib import colors
from reportlab.lib.enums import TA_CENTER, TA_LEFT
from reportlab.platypus import (
    BaseDocTemplate, PageTemplate, Frame, Paragraph, Spacer, Table, TableStyle,
    KeepTogether, HRFlowable, ListFlowable, ListItem,
)
from reportlab.lib.styles import ParagraphStyle

# ── Paleta da marca ──
BROWN   = colors.HexColor("#B07E54")
TEXT    = colors.HexColor("#33291F")
MUTED   = colors.HexColor("#7C7065")
FAINT   = colors.HexColor("#A79C91")
RULE    = colors.HexColor("#E2D7CB")
CREAM   = colors.HexColor("#F7F3EF")
SURF    = colors.HexColor("#FBF8F4")
GOOD    = colors.HexColor("#5F7F63")
GOODBG  = colors.HexColor("#ECF2EC")
BAD     = colors.HexColor("#A85A4A")
BADBG   = colors.HexColor("#F7EBE7")

SERIF = "Times-Roman"
SERIF_I = "Times-Italic"
SANS = "Helvetica"
SANS_B = "Helvetica-Bold"

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
    "tdb": ParagraphStyle("tdb", fontName=SANS_B, fontSize=8.5, leading=12, textColor=TEXT),
    "foot": ParagraphStyle("foot", fontName=SANS, fontSize=7.5, leading=11,
                           textColor=FAINT, alignment=TA_CENTER),
}

def P(t, s="body"): return Paragraph(t, styles[s])

def bullets(items):
    return ListFlowable(
        [ListItem(P(t, "li"), leftIndent=10, value="•") for t in items],
        bulletType="bullet", start="•", leftIndent=12, bulletColor=BROWN,
    )

def headline(fig, label):
    inner = Table([[P(fig, "figure")], [P(label, "figlabel")]], colWidths=[150*mm])
    inner.setStyle(TableStyle([
        ("BOX", (0,0), (-1,-1), 0.6, RULE),
        ("BACKGROUND", (0,0), (-1,-1), SURF),
        ("TOPPADDING", (0,0), (-1,-1), 12),
        ("BOTTOMPADDING", (0,0), (-1,-1), 12),
    ]))
    return inner

def formula_box(line, label):
    big = ParagraphStyle("fbig", fontName=SERIF, fontSize=14, leading=19,
                         textColor=BROWN, alignment=TA_CENTER)
    inner = Table([[Paragraph(line, big)], [P(label, "figlabel")]], colWidths=[150*mm])
    inner.setStyle(TableStyle([
        ("BOX", (0,0), (-1,-1), 0.6, RULE),
        ("BACKGROUND", (0,0), (-1,-1), SURF),
        ("TOPPADDING", (0,0), (-1,-1), 10),
        ("BOTTOMPADDING", (0,0), (-1,-1), 10),
    ]))
    return inner

def note(html, kind="note"):
    bar = BROWN if kind == "note" else BAD
    bg = colors.white if kind == "note" else BADBG
    t = Table([[P(html, "note")]], colWidths=[150*mm])
    t.setStyle(TableStyle([
        ("LINEBEFORE", (0,0), (0,-1), 2, bar),
        ("BACKGROUND", (0,0), (-1,-1), bg),
        ("LEFTPADDING", (0,0), (-1,-1), 10),
        ("RIGHTPADDING", (0,0), (-1,-1), 10),
        ("TOPPADDING", (0,0), (-1,-1), 8),
        ("BOTTOMPADDING", (0,0), (-1,-1), 8),
    ]))
    return t

def table(headers, rows, aligns, widths, bad_cells=None, good_cells=None):
    bad_cells = bad_cells or set()
    good_cells = good_cells or set()
    data = [[P(h, "th") for h in headers]]
    for r in rows:
        data.append([P(c, "td") for c in r])
    t = Table(data, colWidths=[w*mm for w in widths], repeatRows=1)
    ts = [
        ("LINEBELOW", (0,0), (-1,0), 0.8, RULE),
        ("LINEBELOW", (0,1), (-1,-2), 0.4, RULE),
        ("TOPPADDING", (0,0), (-1,-1), 5),
        ("BOTTOMPADDING", (0,0), (-1,-1), 5),
        ("LEFTPADDING", (0,0), (-1,-1), 0),
        ("RIGHTPADDING", (0,0), (-1,-1), 6),
        ("VALIGN", (0,0), (-1,-1), "TOP"),
    ]
    for col, a in enumerate(aligns):
        ts.append(("ALIGN", (col,0), (col,-1), a))
    t.setStyle(TableStyle(ts))
    return t

# ── Documento ──
def build():
    story = []

    # Masthead
    story.append(Spacer(1, 6*mm))
    story.append(P("Plano de Marketing", "h1"))
    story.append(P("OURMOMENT · 12 MESES · REVISTO JULHO 2026", "kicker"))
    story.append(Spacer(1, 3*mm))
    story.append(HRFlowable(width="100%", thickness=0.6, color=RULE, spaceAfter=6))

    # 1. A regra
    story.append(P("A regra que decide tudo o resto", "h2"))
    story.append(P("Antes de escolher canais, é preciso saber quanto podes pagar por cliente. Ainda não fixaste preços — por isso o que importa agora não é um número, é a regra.", "dek"))
    story.append(formula_box("MARGEM  ≥  CUSTO DE AQUISIÇÃO", "NUNCA PAGUES POR UM CLIENTE MAIS DO QUE A MARGEM QUE ELE TE DEIXA"))
    story.append(Spacer(1, 8))
    story.append(P("A tua <b>margem de contribuição</b> por encomenda é o teto absoluto do que podes gastar para conquistar um cliente. Assim que definires preços, calcula-a com esta conta:", "body"))
    story.append(formula_box("Preço ÷ 1,23  −  Produção  −  Portes  −  (1,5% + €0,25)  −  ~€0,90",
                             "IVA  ·  CUSTO POD  ·  ENVIO  ·  TAXA STRIPE  ·  CUSTOMILY  =  MARGEM"))
    story.append(Spacer(1, 8))
    story.append(note("<b>Fecha esta conta antes de gastar em tráfego</b> — é ela que decide se o negócio dá lucro. O Customily tira ~€0,90 por artigo à margem; inclui-o sempre. E a percentagem de margem não paga anúncios — os <b>euros por encomenda</b> pagam. Otimiza bundles para euros, não para percentagem."))
    story.append(Spacer(1, 4))
    story.append(note("<b>A regra de ouro.</b> Se conquistar uma venda te custar mais do que a margem dessa venda, estás a pagar para vender. Todo este plano existe para manter o custo de aquisição abaixo da margem — seja qual for o preço que vieres a definir."))

    # 2. Diferenciação (NOVO)
    story.append(P("Como te diferencias — cumprir é a arma", "h2"))
    story.append(P("O concorrente a bater é o Ponto de Amor®: a melhor marca emocional do nicho, mas falha na operação — atrasos, qualidade e apoio. Não o venças na emoção; vence-o onde a promessa dele parte.", "dek"))
    story.append(bullets([
        "<b>Preview honesto.</b> Com o Customily, o cliente vê o resultado final antes de comprar — \"o que vês é o que recebes\". Ataca a queixa nº1 contra o líder (fotos que não correspondem).",
        "<b>Prazos honestos, não rápidos.</b> Como é print-on-demand, não prometas rapidez que não controlas. Diz a verdade sobre os prazos — a transparência é, por si, diferenciação.",
        "<b>Casais como unidade.</b> O líder vende \"Para Ela / Para Ele\". Um nicho exclusivo de casais é território livre.",
        "<b>Nicho dentro de casais.</b> Aniversário de relação, casais à distância, \"primeira casa juntos\" — mais defensável que \"presentes para casais\" em geral.",
        "<b>Ritual e experiência, não descontos.</b> O líder treina o cliente a esperar promoção (\"leve 2 pague 1\"), o que erode margem e marca. Compete por ritual da oferta, não por preço.",
    ]))

    # 3. Porque não anunciar
    story.append(P("Porque não deves anunciar no primeiro dia", "h2"))
    story.append(P("Uma loja nova converte perto de 1%. A esse ritmo, cada venda vinda de anúncios custa isto — custo por clique a dividir pela taxa de conversão:", "dek"))
    story.append(table(
        ["CPC", "Conversão 1%", "Conversão 2%", "Conversão 3%"],
        [["€0,15","€15","€7,50","€5"],
         ["€0,25","€25","€12,50","€8,33"],
         ["€0,35","€35","€17,50","€11,67"]],
        ["LEFT","RIGHT","RIGHT","RIGHT"],
        [25,42,42,41],
    ))
    story.append(Spacer(1, 6))
    story.append(P("Compara cada valor com a tua margem. À conversão de ~1% de uma loja nova (coluna da esquerda), o custo por venda é quase de certeza maior do que a margem — dás prejuízo em cada venda. Não é falta de habilidade; é matemática.", "body"))
    story.append(note("Os anúncios não são a alavanca — são o <b>multiplicador</b>. Multiplicam o que já funciona, e ainda não tens nada a multiplicar. Primeiro constrói conversão e provas sociais no orgânico; só depois, com o custo por venda abaixo da margem, deitas gasolina na fogueira. Resiste a anunciar antes de teres 30 avaliações e conversão acima de 1,5%.", "warn"))

    # 4. Fases
    story.append(P("O plano, em quatro fases", "h2"))
    story.append(P("Cada fase tem um critério de saída. Não avances sem o cumprir — é assim que se evita queimar dinheiro.", "dek"))

    phases = [
        ("MÊS 1–2", "Fundações e prova de produto",
         "Primeiras 10 vendas · Marketing €0 · Amostras ~€100", [
            "<b>Encomenda amostras</b> de cada produto e fotografa-as em casa com luz natural. Os mockups do fornecedor não vendem emoção.",
            "<b>Vende aos círculos próximos</b> — as primeiras 10 avaliações e fotos reais de clientes.",
            "<b>Abre TikTok, Instagram e Pinterest</b> (empresa, Rich Pins). Publica o processo, não vendas.",
            "<b>Instala o Meta Pixel e o Google Analytics</b> já — o pixel precisa de meses a aprender.",
            "<b>Testa o fluxo completo</b> (personalização → pagamento → Printify) com uma encomenda antes de gastar em tráfego.",
         ]),
        ("MÊS 3–5", "Motor de conteúdo orgânico",
         "50 vendas · conversão >1,5% · Marketing ~€30/mês", [
            "<b>TikTok e Reels, 5×/semana.</b> O formato-rei: a reação de quem recebe o presente — propaga-se sozinho.",
            "<b>Pinterest, 3 pins/dia.</b> É um motor de busca; as pessoas planeiam presentes lá com meses de antecedência.",
            "<b>Semeia com micro-influencers</b> de casais (5–30 mil seguidores). Pagas em produto, não em dinheiro.",
            "<b>Lista de email + carrinho abandonado</b> (3 emails automáticos). A única automação que se paga desde o dia 1.",
            "<b>Pede avaliação com foto</b> 10 dias após a entrega.",
         ]),
        ("MÊS 6–8", "Aumentar o valor por encomenda",
         "Subir a encomenda média · Marketing ~€120/mês", [
            "<b>Bundles</b> \"quadro + caneca a condizer\". Sobe os euros por encomenda e dilui os portes.",
            "<b>Embrulho de oferta</b> pago — margem quase pura e reforça a promessa da marca.",
            "<b>Retargeting</b> a quem visitou e não comprou — o primeiro euro de publicidade que faz sentido.",
            "<b>Só agora testa anúncios frios</b>, e só se a conversão passou 1,5%. Mata qualquer conjunto cujo custo por venda passe a tua margem.",
         ]),
        ("MÊS 9–12", "Escalar o que funciona",
         "Rentabilidade sustentada · Marketing: 30% da margem", [
            "<b>Sobe os anúncios 20%/semana</b>, nunca mais — saltos maiores reiniciam a aprendizagem.",
            "<b>SEO começa a pagar</b> — o conteúdo do mês 3 posiciona-se. Tráfego orgânico tem CAC zero.",
            "<b>Afiliados</b> com fotógrafos de casamento e wedding planners — vendem ao teu cliente exato.",
            "<b>Expande para Espanha</b> — mesmo fornecedor europeu, mercado cinco vezes maior.",
         ]),
    ]
    for when, title, goal, items in phases:
        block = [
            HRFlowable(width="100%", thickness=0.4, color=RULE, spaceBefore=6, spaceAfter=6),
            P(f'<font color="#B07E54"><b>{when}</b></font>&nbsp;&nbsp;<font name="Times-Roman" size="12">{title}</font>', "body"),
            P(f'<font color="#A79C91">{goal}</font>', "li"),
            Spacer(1, 3),
            bullets(items),
        ]
        story.append(KeepTogether(block))

    # 5. Canais
    story.append(P("Canais, por ordem de retorno", "h2"))
    story.append(P("Com orçamento quase nulo, a ordem importa mais que a execução. Faz o primeiro bem antes de tocar no último.", "dek"))
    story.append(table(
        ["Canal", "Custo", "Retorno", "Porquê para ti"],
        [["TikTok / Reels","Grátis","Alto, lento","O teu produto tem um momento de reação filmável."],
         ["Pinterest","Grátis","Alto, composto","Motor de busca; as pessoas planeiam presentes lá."],
         ["Email","Grátis até 250","Muito alto","Carrinho abandonado e aniversários. A lista é tua."],
         ["Seeding a influencers","1 produto","Médio","Pagas em produto, não em dinheiro; gera conteúdo reutilizável."],
         ["SEO","Grátis","Alto, muito lento","6 meses a dar frutos. Planta agora, colhe no Natal."],
         ["Retargeting","€3–5/dia","Alto","Público quente. O 1º euro de publicidade a gastar."],
         ["Anúncios frios","€10+/dia","Negativo no início","Só depois de conversão >1,5%."]],
        ["LEFT","LEFT","LEFT","LEFT"],
        [34,26,28,62],
    ))

    # 6. Calendário
    story.append(P("O calendário manda mais do que tu", "h2"))
    story.append(P("Presentes para casais é um negócio de picos — mas os \"meses mortos\" decidem se sobrevives o ano todo.", "dek"))
    story.append(table(
        ["Quando", "Ocasião", "Preparar"],
        [["14 Fev","Dia dos Namorados","Começa a 5 Jan. Fecha encomendas a 2 Fev."],
         ["Mai / Mar","Dia da Mãe e do Pai","Ângulo de família, não só casal."],
         ["Jun–Set","Época de casamentos","Afiliados com fotógrafos e wedding planners."],
         ["Fim Nov","Black Friday","Desconta bundles, nunca produtos individuais."],
         ["Dezembro","Natal","Até 40% do ano. Prazo de encomenda a 10 Dez."],
         ["Todo o ano","Aniversários de relação","Conteúdo evergreen para os meses mortos."]],
        ["LEFT","LEFT","LEFT"],
        [28,42,80],
    ))
    story.append(Spacer(1, 6))
    story.append(note("<b>O prazo de encomenda é sagrado.</b> Um presente de Natal que chega a 27 de dezembro não é um atraso — é uma devolução, uma estrela e um cliente perdido. Anuncia a data-limite no topo do site a partir de 1 de dezembro. É aqui que o Ponto de Amor® falha; é aqui que ganhas."))

    # 7. Orçamento
    story.append(P("Orçamento de marketing, mês a mês", "h2"))
    story.append(P("Um negócio que começa do zero. Cada euro sai da margem que a venda anterior gerou — nunca do teu bolso depois do mês 3.", "dek"))
    story.append(table(
        ["Mês", "Onde vai o dinheiro", "€/mês", "Acumulado"],
        [["1–2","Nada. Só orgânico e amostras.","0","0"],
         ["3–5","Seeding: 2 produtos/mês","30","90"],
         ["6–8","Retargeting €3/dia · seeding","120","450"],
         ["9–12","Anúncios frios, só se custo/venda < margem","150–250","1.050–1.450"]],
        ["LEFT","LEFT","RIGHT","RIGHT"],
        [18,72,30,30],
    ))
    story.append(Spacer(1, 6))
    story.append(note("<b>A regra dos 30%.</b> A partir do momento em que vendes, reinveste 30% da margem em marketing e guarda o resto. Nunca gastes dinheiro que a loja ainda não ganhou. Em print-on-demand não há stock parado — a única forma de ires à falência é comprar tráfego mais caro do que o que ele devolve."))

    # 8. Custos (ATUALIZADO com Customily)
    story.append(P("Quanto vais pagar", "h2"))
    story.append(P("Custos mensais de operar a loja, já com o Customily como motor de personalização.", "dek"))
    story.append(table(
        ["Item", "Plano", "€/mês"],
        [["Alojamento","EasyWP","4"],
         ["Domínio","~€12/ano","1"],
         ["Email profissional","Zoho Mail","1"],
         ["Customily","Personalizador ($49, dividido por 3)","≈15"],
         ["Printify","Grátis — paga-se por venda","0"],
         ["WooCommerce, Yoast, Complianz","Gratuitos","0"],
         ["Stripe","1,5% + €0,25 por venda","0"]],
        ["LEFT","LEFT","RIGHT"],
        [46,66,18],
    ))
    story.append(Spacer(1, 6))
    story.append(note("O Customily custa <b>$49/mês</b> mais ~€0,90 por artigo personalizado. Dividido pelas 3 pessoas, a parte fixa por cabeça é ~€15/mês — e a comissão por artigo só existe quando já vendeste. <b>Investimento inicial único:</b> ~€100 em amostras (inegociável) + revisão jurídica opcional."))

    # 9. KPIs
    story.append(P("Quando é seguro acelerar", "h2"))
    story.append(P("Não avances por sentires que é a altura. Avança quando estes números o disserem.", "dek"))
    story.append(table(
        ["Métrica", "Agora", "Meta", "Porquê importa"],
        [["Taxa de conversão","—",">1,5%","Abaixo disto, os anúncios dão prejuízo."],
         ["Encomenda média","—","subir","Cada euro aqui é custo de aquisição que podes pagar."],
         ["Custo por venda","€0","< margem","Nunca acima da margem de contribuição."],
         ["Avaliações","0","30+","A conversão duplica entre 0 e 30 avaliações."],
         ["Lista de email","0","500","Deve valer 25% da receita."],
         ["Compras repetidas","—",">15%","Aniversários repetem-se todos os anos."]],
        ["LEFT","RIGHT","RIGHT","LEFT"],
        [34,18,18,80],
    ))
    story.append(Spacer(1, 6))
    story.append(note("<b>A alavanca mais barata não é o marketing.</b> Subir a conversão de 1% para 2% duplica as vendas sem gastares um cêntimo. Antes de pagar por mais tráfego, arranca mais valor do que já tens: fotos reais, avaliações visíveis, prazos claros e um checkout curto."))

    # 10. Amanhã
    story.append(P("O que fazer a seguir", "h2"))
    story.append(bullets([
        "<b>Testa uma encomenda de ponta a ponta</b> — personalização → pagamento → Printify com o design certo. É o único que pode esconder surpresas.",
        "<b>Encomenda as amostras</b> e fotografa-as. Não podes vender o que nunca tiveste na mão.",
        "<b>Abre TikTok e Pinterest</b> e publica o primeiro vídeo do desembrulhar. Ninguém vai ver. Publica na mesma.",
        "<b>Vende à primeira pessoa que conheces</b> e pede uma avaliação com foto. Vale mais que os primeiros €100 de anúncios.",
    ]))

    story.append(Spacer(1, 10))
    story.append(HRFlowable(width="100%", thickness=0.5, color=RULE, spaceAfter=6))
    story.append(P("Ainda sem preços de venda definidos: o plano assenta na regra margem ≥ custo de aquisição, seja qual for o preço. IVA a 23%, Stripe a 1,5% + €0,25. Concorrência e posicionamento com base no Estudo de Mercado (julho 2026).", "foot"))

    # Render
    doc = BaseDocTemplate(
        "marketing-plan.pdf", pagesize=A4,
        leftMargin=22*mm, rightMargin=22*mm, topMargin=18*mm, bottomMargin=16*mm,
        title="Plano de Marketing — OurMoment", author="OurMoment",
    )
    frame = Frame(doc.leftMargin, doc.bottomMargin,
                  doc.width, doc.height, id="main")

    def footer(canvas, d):
        canvas.saveState()
        canvas.setFont(SANS, 7)
        canvas.setFillColor(FAINT)
        canvas.drawCentredString(A4[0]/2, 9*mm,
            f"OurMoment · Plano de Marketing · {d.page}")
        canvas.restoreState()

    doc.addPageTemplates([PageTemplate(id="main", frames=[frame], onPage=footer)])
    doc.build(story)
    print("PDF criado: marketing-plan.pdf")

if __name__ == "__main__":
    build()
