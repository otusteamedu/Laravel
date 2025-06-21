<div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button> 
        <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>

    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('images/slide1.jpg') }}" alt="" height="100%" width="100%" class="object-fit-cover">
            <div class="container">
                <div class="carousel-caption text-start">
                    <h1 class="opacity-75 bg-dark d-inline-block">Смартфон мечты — всего один клик!</h1>
                    <br>
                    <p class="opacity-75 bg-dark d-inline-block">Покупка нового смартфона стала проще простого</p>
                </div>
            </div>
        </div>

        <div class="carousel-item">
            <img src="{{ asset('images/slide2.jpg') }}" alt="" height="100%" width="100%" class="object-fit-cover">
            <div class="container">
                <div class="carousel-caption">
                    <h1 class="opacity-75 bg-dark d-inline-block">Ваш новый смартфон ждёт вас онлайн!</h1>
                    <br>
                    <p class="opacity-75 bg-dark d-inline-block">Хочешь больше возможностей? Купи новый смартфон</p>
                </div>
            </div>
        </div>

        <div class="carousel-item">
            <img src="{{ asset('images/slide3.jpg') }}" alt="" height="100%" width="100%" class="object-fit-cover">
            <div class="container">
                <div class="carousel-caption text-end">
                    <h1 class="opacity-75 bg-dark d-inline-block">Лучшие телефоны — лучшие цены!</h1>
                    <br>
                    <p class="opacity-75 bg-dark d-inline-block">Идеально подходит вашему стилю жизни</p>
                </div>
            </div>
        </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Назад</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span> 
        <span class="visually-hidden">Вперед</span>
    </button>
</div>

<div class="container marketing">
    <div class="row">
        <div class="col-lg-4"> 
            <img src="{{ asset('images/output1.jpg') }}" alt="" class="rounded-circle" height="140" width="140">
            <h2 class="fw-normal">Высокое качество камеры</h2>
            <p>Хороший телефон для маркетинговых целей должен обладать рядом ключевых характеристик, которые позволяют эффективно решать задачи продвижения продуктов и услуг</p>
        </div>

        <div class="col-lg-4"> 
            <img src="{{ asset('images/output2.jpg') }}" alt="" class="rounded-circle" height="140" width="140">
            <h2 class="fw-normal">Мощная производительность</h2>
            <p>Телефон должен быстро обрабатывать большие объемы данных, поддерживать многозадачность и обеспечивать плавность работы приложений для социальных сетей, аналитики, CRM</p>
        </div>

        <div class="col-lg-4"> 
            <img src="{{ asset('images/output3.jpg') }}" alt="" class="rounded-circle" height="140" width="140">
            <h2 class="fw-normal">Длительное время автономной работы</h2>
            <p>Работа часто связана с командировками, мероприятиями и выездами. Поэтому важно, чтобы смартфон мог долго функционировать от одного заряда батареи — желательно минимум один полный рабочий день</p>
        </div>
    </div>

    <hr class="featurette-divider">
    <div class="row featurette">
        <div class="col-md-7">
            <h2 class="featurette-heading fw-normal lh-1">
                Поддержка нескольких SIM-карт
                <span class="text-body-secondary">Быстрое подключение</span>
            </h2>
            <p class="lead">Наличие двух слотов позволит комфортно совмещать рабочие и личные звонки</p>
        </div>
        <div class="col-md-5">
            <img src="{{ asset('images/square1.jpg') }}" alt="" height="500" width="500"
                class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto">
        </div>
    </div>

    <hr class="featurette-divider">
    <div class="row featurette">
        <div class="col-md-7 order-md-2">
            <h2 class="featurette-heading fw-normal lh-1">
                    Удобство пользования
                <span class="text-body-secondary">Качественная сборка корпуса</span>
            </h2>
            <p class="lead">Экран высокого разрешения обеспечит четкое отображение графиков, таблиц и презентаций</p>
        </div>
        <div class="col-md-5 order-md-1">
            <img src="{{ asset('images/square2.jpg') }}" alt="" height="500" width="500"
                class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto">
        </div>
    </div>

    <hr class="featurette-divider">
    <div class="row featurette">
        <div class="col-md-7">
            <h2 class="featurette-heading fw-normal lh-1">
                Надежность и безопасность
                <span class="text-body-secondary">Защита данных</span>
            </h2>
            <p class="lead">Шифрование файлов, сканеры отпечатков пальцев, распознавание лица и другие меры безопасности</p>
        </div>
        <div class="col-md-5">
            <img src="{{ asset('images/square3.jpg') }}" alt="" height="500" width="500"
                class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto">
        </div>
    </div>
</div>