import styles from "./AboutAcademy.module.css";
<<<<<<< HEAD

export function AboutAcademy() {
  return (
    <section id="about" className={styles.section}>
      <div className={styles.container}>
        <p className={styles.kicker}>О АКАДЕМИИ</p>
        <h2 className={styles.title}>Система профессионального входа в индустрию</h2>
        <p className={styles.text}>
          BONSHERY — международная академия груминга, в которой формируется не просто навык,
          а профессиональное мышление специалиста. Здесь вы учитесь понимать, чувствовать
          и работать на уровне, за который готовы платить клиенты.
        </p>
        <p className={styles.text}>
          Мы создаём среду, в которой ученики растут быстрее, потому что находятся внутри
          реальной индустрии, а не учебной имитации.
        </p>
=======
import AboutAccordion from "./AboutAccordion";

export function AboutAcademy() {
  return (
  <section id="about" className={styles.section}>
      <div className={styles.container}>
        <div className={styles.flexWrap}>
          <div className={styles.photoWrap}>
            <img src="/img/image-1774391833091.png" alt="Основатель академии" className={styles.photo} />
          </div>
          <div className={styles.contentWrap}>
            <p className={styles.kicker}>Об академии</p>
            <h2 className={styles.title}>Система профессионального входа в индустрию</h2>
            <p className={styles.text}>
              BONSHERY — международная академия груминга, в которой формируется не просто навык,
              а профессиональное мышление специалиста. Здесь вы учитесь понимать, чувствовать
              и работать на уровне, за который готовы платить клиенты.
            </p>
            <p className={styles.text}>
              Мы создаём среду, в которой ученики растут быстрее, потому что находятся внутри
              реальной индустрии, а не учебной имитации.
            </p>
          </div>
        </div>

        {/* КУРСЫ С НУЛЯ */}
        <section id="courses" className={styles.block}>
          <h2 className={styles.blockTitle}>Курсы с нуля: системный вход в профессию грумера</h2>
          <div className={styles.coursesWrap}>
            {/* image removed to avoid duplicate — visual left intentionally empty */}
            <div className={styles.coursesTextWrap}>
              <p className={styles.blockText}>Это не обучение “для галочки”</p>
              <p className={styles.blockText}>Это система, в которой вы проходите путь<br/>от полного отсутствия навыков<br/>до уверенной работы с клиентами</p>
              <p className={styles.blockText}>Вы учитесь не просто стричь<br/>а понимать животных, управлять процессом<br/>и создавать результат, за который готовы платить</p>
            </div>
          </div>
          <div className={styles.coursesCards}>
            <div className={styles.courseCard}>
              <h3 className={styles.courseCardTitle}>Курс с нуля — Классика (7 дней)</h3>
              <p className={styles.courseCardDesc}>Базовая программа, которая даёт уверенный вход в профессию</p>
              <ul className={styles.courseList}>
                <li>основы груминга</li>
                <li>работа с инструментами</li>
                <li>базовые техники стрижек</li>
                <li>практика на моделях</li>
                <li>разбор ошибок</li>
              </ul>
              <div className={styles.courseResult}>Вы понимаете, как устроена профессия и можете выполнять базовые работы</div>
              <div className={styles.courseFor}>Кому подойдёт:<br/>— если вы только начинаете<br/>— если хотите попробовать профессию<br/>— если нужен понятный старт</div>
              <a href="#enrollment" className={styles.courseBtn}>Выбрать курс</a>
            </div>
            <div className={styles.courseCard}>
              <h3 className={styles.courseCardTitle}>Курс с нуля — Интенсив (10 дней)</h3>
              <p className={styles.courseCardDesc}>Расширенная программа с максимальной практикой и более глубоким погружением в профессию</p>
              <ul className={styles.courseList}>
                <li>углублённая практика</li>
                <li>больше моделей</li>
                <li>отработка техник</li>
                <li>работа с разными типами шерсти</li>
                <li>уверенная постановка руки</li>
              </ul>
              <div className={styles.courseResult}>Вы выходите с более уверенным навыком и готовностью работать с клиентами</div>
              <div className={styles.courseFor}>Кому подойдёт:<br/>— если хотите быстрее выйти в профессию<br/>— если важна практика<br/>— если нужен сильный результат</div>
              <a href="#enrollment" className={styles.courseBtn}>Выбрать курс</a>
            </div>
          </div>
        </section>

        {/* БЛОК ПОДДЕРЖКИ */}
        <section id="support" className={styles.block}>
          <div className={styles.supportIcon}></div>
          <div>
            <h3 className={styles.blockTitle}>Вы не остаётесь одни после курса</h3>
            <p className={styles.blockText}>После обучения вы получаете доступ в закрытый канал академии</p>
            <ul className={styles.supportList}>
              <li>дополнительные материалы</li>
              <li>разборы работ и ошибок</li>
              <li>ответы на вопросы</li>
              <li>рекомендации по инструментам и оборудованию</li>
              <li>поддержка на этапе начала работы</li>
            </ul>
            <div className={styles.supportPhrase}>Мы не отпускаем вас после обучения — мы сопровождаем вас до результата</div>
          </div>
        </section>

        {/* БЛОК СРАВНЕНИЯ */}
        <section id="compare" className={styles.block}>
          <h3 className={styles.blockTitle}>В чём разница между курсами</h3>
          <div className={styles.compareGrid}>
            <div className={styles.compareCard}>
              <div className={styles.compareIcon}></div>
              <div className={styles.compareTitle}>Классика — 7 дней</div>
              <ul className={styles.compareList}>
                <li>базовое обучение</li>
                <li>знакомство с профессией</li>
                <li>фундамент</li>
                <li>меньше практики</li>
              </ul>
            </div>
            <div className={styles.compareCard}>
              <div className={styles.compareIcon}></div>
              <div className={styles.compareTitle}>Интенсив — 10 дней</div>
              <ul className={styles.compareList}>
                <li>больше практики</li>
                <li>глубже погружение</li>
                <li>быстрее рост</li>
                <li>выше уверенность</li>
              </ul>
            </div>
          </div>
          <div className={styles.compareKeyText}><b>Разница не в информации</b><br/>Разница в количестве практики и уровне уверенности, с которым вы выходите</div>
          <div className={styles.compareKeyText}><span className={styles.compareKeyMark}>👉 7 дней — вы понимаете</span><br/><span className={styles.compareKeyMark}>👉 10 дней — вы уже делаете</span></div>
        </section>

        {/* БЛОК ВЫБОРА */}
        <section id="choose" className={styles.block}>
          <h3 className={styles.blockTitle}>Какой курс выбрать</h3>
          <div className={styles.chooseGrid}>
            <div className={styles.chooseCol}>
              <div className={styles.chooseIcon}></div>
              <div className={styles.chooseText}>Если вы хотите аккуратно войти в профессию —<br/><b>выбирайте классику</b></div>
            </div>
            <div className={styles.chooseCol}>
              <div className={styles.chooseIcon}></div>
              <div className={styles.chooseText}>Если ваша задача — быстрее начать работать и чувствовать уверенность —<br/><b>выбирайте интенсив</b></div>
            </div>
          </div>
        </section>

        {/* ФИНАЛЬНЫЙ ДОЖИМ */}
        <section id="start-now" className={styles.block}>
          <div className={styles.finalIcon}></div>
          <div>
            <h3 className={styles.blockTitle}>Вы можете начать сейчас</h3>
            <p className={styles.blockText}>Профессия не становится проще со временем<br/>Но становится понятнее, если вы обучаетесь в системе</p>
            <div className={styles.finalBtns}>
              <a href="#enrollment" className={styles.courseBtn}>Выбрать курс</a>
              <a href="#contacts" className={styles.courseBtnAlt}>Получить консультацию</a>
            </div>
          </div>
        </section>

        {/* БАННЕР С ДАТАМИ */}
        <section id="dates" className={styles.block}>
          <h3 className={styles.blockTitle}>Ближайшая запись на курсы</h3>
          <div className={styles.datesSubTitle}>Выберите удобную дату и начните обучение</div>
          <div className={styles.datesCards}>
            <div className={styles.datesCard}>
              <div className={styles.datesCardIcon}></div>
              <div className={styles.datesCardTitle}>Курс с нуля — Классика (7 дней)</div>
              <div className={styles.datesCardDate}>(дата из админки)</div>
              <div className={styles.datesCardFormat}>Офлайн</div>
              <a href="#enrollment" className={styles.datesCardBtn}>Записаться</a>
            </div>
            <div className={styles.datesCard}>
              <div className={styles.datesCardIcon}></div>
              <div className={styles.datesCardTitle}>Курс с нуля — Интенсив (10 дней)</div>
              <div className={styles.datesCardDate}>(дата из админки)</div>
              <div className={styles.datesCardFormat}>Офлайн</div>
              <a href="#enrollment" className={styles.datesCardBtn}>Записаться</a>
            </div>
          </div>
          <div className={styles.datesNote}>Количество мест ограничено<br/>Если вы не уверены, какой формат выбрать — оставьте заявку, и мы подберём программу под вас</div>
          <div className={styles.datesBtns}>
            <a href="#enrollment" className={styles.datesCardBtn}>Записаться</a>
            <a href="#contacts" className={styles.datesCardBtnAlt}>Получить консультацию</a>
          </div>
        </section>

        {/* МАСТЕР-КЛАССЫ */}
        <section id="masterclasses" className={styles.block}>
          <h2 className={styles.blockTitle}>Мастер-классы: рост уровня и работа в профессиональной среде</h2>
          <div className={styles.blockSubTitle}>Обучение для мастеров, которые хотят выйти на следующий уровень и работать на уровне индустрии</div>
          <AboutAccordion />
        </section>

        {/* МЕЖДУНАРОДНЫЙ АКЦЕНТ */}
        <section id="international-accent" className={styles.block}>
          <h3 className={styles.blockTitle}>Международный опыт</h3>
          <p className={styles.blockText}>На площадке BONSHERY проходят мастер-классы с участием ведущих грумеров из разных стран<br/>Вы обучаетесь у специалистов, которые формируют индустрию<br/>И работаете в среде, где задаются стандарты качества</p>
          <div className={styles.masterFor}>Кому подойдёт:<br/>— действующим мастерам<br/>— выпускникам курсов<br/>— тем, кто хочет расти<br/>— тем, кто хочет повысить чек</div>
        </section>

        {/* РЕЗУЛЬТАТ */}
        <section id="master-result" className={styles.block}>
          <h3 className={styles.blockTitle}>Что меняется после мастер-классов</h3>
          <p className={styles.blockText}>Вы начинаете видеть свои ошибки и понимать, как их исправить<br/>Ваши работы становятся чище, увереннее и профессиональнее<br/>А вместе с этим растёт и ваша ценность как мастера</p>
        </section>

        {/* СВЯЗКА С РОСТОМ */}
        <section id="growth-link" className={styles.block}>
          <p className={styles.blockText}>Мастер-классы — это следующий шаг после базового обучения<br/>Именно здесь начинается переход от “умею стричь” к “работаю на уровне”</p>
        </section>

        {/* CTA */}
        <section id="master-cta" className={styles.block}>
          <h3 className={styles.blockTitle}>Выберите мастер-класс</h3>
          <div className={styles.ctaBtns}>
            <a href="#masterclasses" className={styles.courseBtn}>Смотреть расписание</a>
            <a href="#contacts" className={styles.courseBtnAlt}>Получить консультацию</a>
          </div>
        </section>
>>>>>>> 6392f78 (fix: центрирование фото в блоке FounderSection)
      </div>
    </section>
  );
}
