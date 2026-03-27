"use client";

import { useState } from "react";
import styles from "./GraduateVideo.module.css";

type Category = "students" | "clients" | "masterclass";

type Review = {
  name: string;
  role: string;
  course: string;
  result: string;
  quote: string;
  photo: string;
  videoUrl?: string;
};

const categoryLabels: Record<Category, string> = {
  students: "Отзывы учеников",
  clients: "Отзывы клиентов салона",
  masterclass: "Отзывы о мастер-классах",
};

const reviewsByCategory: Record<Category, Review[]> = {
  students: [
    {
      name: "Елена Р.",
      role: "Сменила профессию после банка",
      course: "Курс Классика",
      result: "Через 2 месяца после выпуска взяла первых постоянных клиентов и вышла на стабильный график.",
      quote: "С нуля пришла в профессию и уже работаю по записи. Курс окупился быстро.",
      photo: "/images/group-photo.jpg",
      videoUrl: "https://www.petsgroomer.ru/vypuskniki/",
    },
    {
      name: "Марина К.",
      role: "Новичок в груминге",
      course: "Курс Базовый",
      result: "Освоила гигиену и коммерческие стрижки популярных пород, собрала портфолио на старте.",
      quote: "После курса перестала бояться брать реальных клиентов. Все по четкому алгоритму.",
      photo: "/images/master-photo.jpg",
    },
  ],
  clients: [
    {
      name: "Ольга, владелец шпица",
      role: "Клиент салона",
      course: "Практика выпускников под контролем преподавателя",
      result: "Собака спокойно перенесла процедуру, стрижка и уход выполнены аккуратно и без стресса.",
      quote: "Вижу высокий уровень работ: чисто, безопасно и очень бережно к собаке.",
      photo: "/images/start-potokov.jpg",
    },
    {
      name: "Анна, владелец пуделя",
      role: "Клиент салона",
      course: "Коммерческий груминг",
      result: "Получила понятный результат под запрос: форма держится, уход дома стал проще.",
      quote: "Сделали именно тот образ, который я хотела. Вернусь снова.",
      photo: "/images/group-photo.jpg",
    },
  ],
  masterclass: [
    {
      name: "Ирина Н.",
      role: "Действующий мастер",
      course: "Мастер-класс по коммерческим формам",
      result: "Подняла средний чек за комплексную услугу после внедрения новых техник и стандартов посадки формы.",
      quote: "После мастер-класса сразу изменила подход к работе и повысила прайс.",
      photo: "/images/master-photo.jpg",
      videoUrl: "https://www.petsgroomer.ru/master_klass/",
    },
    {
      name: "Светлана Т.",
      role: "Руководитель салона",
      course: "Международный курс / повышение квалификации",
      result: "Перестроила внутренние стандарты команды и улучшила качество сложных породных работ.",
      quote: "Материал прикладной: берешь и внедряешь в салоне в тот же месяц.",
      photo: "/images/start-potokov.jpg",
    },
  ],
};

export function GraduateVideo() {
  const [activeCategory, setActiveCategory] = useState<Category>("students");
  const reviews = reviewsByCategory[activeCategory];

  return (
    <section id="graduates" className={styles.section}>
      <div className={styles.container}>
        <div className={styles.head}>
<<<<<<< HEAD
          <p className={styles.kicker}>Социальное доказательство</p>
=======
>>>>>>> 6392f78 (fix: центрирование фото в блоке FounderSection)
          <h2 className={styles.title}>Отзывы с подтверждением результата</h2>
          <p className={styles.subtitle}>
            Коротко и по фактам: что получил человек после обучения или услуги.
          </p>
        </div>

        <div className={styles.tabs} role="tablist" aria-label="Категории отзывов">
          {(Object.keys(categoryLabels) as Category[]).map((category) => (
            <button
              key={category}
              type="button"
              role="tab"
              aria-selected={activeCategory === category}
              className={`${styles.tab} ${activeCategory === category ? styles.tabActive : ""}`}
              onClick={() => setActiveCategory(category)}
            >
              {categoryLabels[category]}
            </button>
          ))}
        </div>

        <div className={styles.grid}>
          {reviews.map((review) => (
            <article key={`${activeCategory}-${review.name}`} className={styles.card}>
              <div className={styles.person}>
                <img src={review.photo} alt={review.name} loading="lazy" />
                <div>
                  <h3 className={styles.name}>{review.name}</h3>
                  <p className={styles.role}>{review.role}</p>
                </div>
              </div>

              <p className={styles.course}>{review.course}</p>
              <p className={styles.result}>{review.result}</p>
              <p className={styles.quote}>&ldquo;{review.quote}&rdquo;</p>

              {review.videoUrl && (
                <a
                  href={review.videoUrl}
                  target="_blank"
                  rel="noreferrer"
                  className={styles.videoLink}
                >
                  Смотреть видеоотзыв
                </a>
              )}
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
