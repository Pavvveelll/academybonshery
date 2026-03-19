"use client";

import { useState } from "react";
import styles from "./FAQ.module.css";

type Question = {
  id: string;
  q: string;
  a: string;
};

const questions: Question[] = [
  {
    id: "cost",
    q: "Это слишком дорого?",
    a: "По опыту выпускников, вложения в обучение и стартовый инструмент окупаются примерно за 3 месяца активной работы.",
  },
  {
    id: "short",
    q: "Нельзя выучиться за одну неделю?",
    a: "Интенсивность занятий и большое количество практики позволяют получить базовые навыки для старта даже за короткий срок.",
  },
  {
    id: "city",
    q: "Я не из Москвы. Как быть?",
    a: "Для иногородних на старом сайте указано бесплатное проживание во многих группах при наличии мест. Важно заранее бронировать место.",
  },
  {
    id: "steps",
    q: "Что делать дальше?",
    a: "Выберите ближайшую группу, оплатите обучение и приходите в назначенный день. Места ограничены, поэтому лучше записываться заранее.",
  },
];

export function FAQ() {
  const [openId, setOpenId] = useState<string>(questions[0].id);

  return (
    <section id="faq" className={styles.section}>
      <div className={styles.container}>
        <h2 className={styles.title}>Сомневаетесь? Вот ответы</h2>
        <div className={styles.list}>
          {questions.map((item) => {
            const opened = openId === item.id;
            return (
              <article key={item.id} className={`${styles.item} ${opened ? styles.itemOpen : ""}`}>
                <button
                  type="button"
                  className={styles.question}
                  onClick={() => setOpenId(opened ? "" : item.id)}
                  aria-expanded={opened}
                >
                  <span>{item.q}</span>
                  <span className={styles.symbol}>{opened ? "−" : "+"}</span>
                </button>
                {opened && <p className={styles.answer}>{item.a}</p>}
              </article>
            );
          })}
        </div>
      </div>
    </section>
  );
}
