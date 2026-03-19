"use client";

import { useState } from "react";
import styles from "./Formats.module.css";

type FormatItem = {
  id: string;
  title: string;
  body: string;
};

const formats: FormatItem[] = [
  {
    id: "classic",
    title: "Курс Классика (7 дней)",
    body: "Самый классический и проверенный временем формат. За 7 насыщенных дней студент отрабатывает практику на разных типах шерсти, получает устойчивые навыки салонного груминга и уверенный старт в профессии.",
  },
  {
    id: "base",
    title: "Курс Базовый (5 дней)",
    body: "Подходит для первого шага в профессии. Формат помогает быстро войти в практику и собрать базу, после которой можно продолжить обучение и стажировку.",
  },
  {
    id: "intensive",
    title: "Курс Интенсив (10 дней)",
    body: "Максимальный объем практики и расширенная программа. Курс ориентирован на тех, кто хочет ускоренно вырасти до уверенного универсального специалиста.",
  },
];

export function Formats() {
  const [active, setActive] = useState<string>(formats[0].id);

  return (
    <section id="formats" className={styles.section}>
      <div className={styles.container}>
        <h2 className={styles.title}>Форматы обучения</h2>
        <p className={styles.subtitle}>
          Обучение состоит из двух частей: онлайн предобучение и практика в Академии.
          Такой подход позволяет эффективно использовать время на занятиях с моделями.
        </p>

        <div className={styles.accordion}>
          {formats.map((item) => {
            const opened = active === item.id;
            return (
              <article key={item.id} className={`${styles.item} ${opened ? styles.itemOpen : ""}`}>
                <button
                  type="button"
                  className={styles.itemButton}
                  onClick={() => setActive(opened ? "" : item.id)}
                  aria-expanded={opened}
                >
                  <span>{item.title}</span>
                  <span className={styles.plus}>{opened ? "−" : "+"}</span>
                </button>

                {opened && <p className={styles.body}>{item.body}</p>}
              </article>
            );
          })}
        </div>
      </div>
    </section>
  );
}
