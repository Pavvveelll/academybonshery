<header class="site-header">
    <div class="site-header__container">
        <img src="/img/photo_2026-03-19_00-26-29.jpg" alt="">
    </div>
</header>

<style>
.site-header {
    width: 100%;
    display: flex;
    justify-content: center; /* центрируем контейнер */
}

.site-header__container {
    width: 100%;
    max-width: 1200px; /* ограничение ширины блока */
    padding: 0 16px; /* отступы по бокам */
    box-sizing: border-box;
}

/* Стили для картинки */
.site-header__container img {
    width: 100%;       /* подстраивается под контейнер */
    height: auto;      /* сохраняет пропорции */
    display: block;    /* убирает лишние отступы */
    object-fit: contain; /* вписывается без обрезки */
}

/* Адаптив */
@media (max-width: 768px) {
    .site-header__container {
        max-width: 100%;
        padding: 0 10px;
    }
}
</style>