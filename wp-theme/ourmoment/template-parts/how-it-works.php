<?php $om_show_title = $args['show_title'] ?? true; ?>
<section id="how-it-works" class="om-how">
  <div class="om-container">
    <?php if ($om_show_title) : ?>
      <h2 class="om-section-title om-fade">Como Funciona</h2>
    <?php endif; ?>
    <div class="om-steps">

      <div class="om-step om-fade">
        <div class="om-step-icon">
          <svg viewBox="0 0 48 48" fill="none" stroke="var(--accent)" stroke-width="1.5">
            <rect x="8" y="6" width="32" height="36" rx="2"/>
            <path d="M16 16 L32 16 M16 24 L32 24 M16 32 L26 32"/>
          </svg>
        </div>
        <span class="om-step-num">1</span>
        <h3>Escolhe a Tua Peça</h3>
        <p>Escolhe uma caneca ou um quadro da nossa coleção — cada um feito para guardar a vossa história.</p>
      </div>

      <div class="om-step om-fade">
        <div class="om-step-icon">
          <svg viewBox="0 0 48 48" fill="none" stroke="var(--accent)" stroke-width="1.5">
            <circle cx="18" cy="16" r="6"/>
            <path d="M6 40 C6 30 12 26 18 26 C24 26 30 30 30 40"/>
            <path d="M34 14 L34 26 M28 20 L40 20"/>
          </svg>
        </div>
        <span class="om-step-num">2</span>
        <h3>Torna-a Vossa</h3>
        <p>Carrega a vossa foto, adiciona os nomes e a data, escolhe o estilo — e vê a pré-visualização ao vivo.</p>
      </div>

      <div class="om-step om-fade">
        <div class="om-step-icon">
          <svg viewBox="0 0 48 48" fill="none" stroke="var(--accent)" stroke-width="1.5">
            <rect x="6" y="16" width="24" height="20" rx="2"/>
            <path d="M30 22 L38 22 L42 28 L42 36 L30 36 Z"/>
            <circle cx="14" cy="40" r="3"/>
            <circle cx="36" cy="40" r="3"/>
            <path d="M12 24 L20 24"/>
          </svg>
        </div>
        <span class="om-step-num">3</span>
        <h3>Imprimimos e Enviamos</h3>
        <p>A tua peça é impressa com cuidado e enviada até à porta, pronta para oferecer.</p>
      </div>

    </div>
  </div>
</section>
