"use client";

import { FormEvent, useMemo, useState } from "react";
import styles from "./Enrollment.module.css";

type CourseProduct = {
  id: string;
  title: string;
  format: string;
  duration: string;
  startDate: string;
  spots: number;
  price: number;
  oldPrice?: number;
  image: string;
  features: string[];
};

type SubmitState = "idle" | "sending" | "success" | "error";

const products: CourseProduct[] = [
  {
    id: "basic",
    title: "Курс Базовый",
    format: "Очный",
    duration: "базовая программа",
    startDate: "12-16 марта",
    spots: 2,
    price: 45900,
    oldPrice: 49900,
    image: "/images/start-potokov.jpg",
    features: ["Практика на моделях", "Инструменты и косметика включены", "Сертификат академии"],
  },
  {
    id: "classic",
    title: "Курс Классика",
    format: "Очный",
    duration: "7 дней",
    startDate: "12-18 марта",
    spots: 3,
    price: 62900,
    oldPrice: 68900,
    image: "/images/group-photo.jpg",
    features: ["Расширенная практика", "Отработка салонных сценариев", "Поддержка после выпуска"],
  },
  {
    id: "intensive",
    title: "Курс Интенсив",
    format: "Очный",
    duration: "10 дней",
    startDate: "12-21 марта",
    spots: 2,
    price: 84900,
    oldPrice: 92900,
    image: "/images/master-photo.jpg",
    features: ["Максимум практики", "Индивидуальная обратная связь", "Подготовка к старту работы"],
  },
];

function formatPrice(value: number): string {
  return new Intl.NumberFormat("ru-RU").format(value);
}

export function Enrollment() {
  const [sortBy, setSortBy] = useState<"date" | "cheap" | "expensive">("date");
  const [activeProduct, setActiveProduct] = useState<CourseProduct | null>(null);
  const [submitState, setSubmitState] = useState<SubmitState>("idle");
  const [submitError, setSubmitError] = useState("");

  const sortedProducts = useMemo(() => {
    const list = [...products];
    if (sortBy === "cheap") {
      list.sort((a, b) => a.price - b.price);
    } else if (sortBy === "expensive") {
      list.sort((a, b) => b.price - a.price);
    }
    return list;
  }, [sortBy]);

  async function handleCheckout(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();

    if (!activeProduct) {
      return;
    }

    const form = event.currentTarget;
    const formData = new FormData(form);
    const name = String(formData.get("name") ?? "").trim();
    const phone = String(formData.get("phone") ?? "").trim();
    const comment = String(formData.get("comment") ?? "").trim();

    if (!phone) {
      setSubmitState("error");
      setSubmitError("Укажите номер телефона для оформления.");
      return;
    }

    const message = [
      "Новая заявка на оформление курса как товара",
      `Курс: ${activeProduct.title}`,
      `Старт: ${activeProduct.startDate}`,
      `Длительность: ${activeProduct.duration}`,
      `Цена: ${formatPrice(activeProduct.price)} руб.`,
      comment ? `Комментарий: ${comment}` : "Комментарий: -",
    ].join("\n");

    setSubmitState("sending");
    setSubmitError("");

    try {
      const response = await fetch("/api/telegram-lead", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ name, phone, message }),
      });

      if (!response.ok) {
        throw new Error("Request failed");
      }

      setSubmitState("success");
      form.reset();
    } catch {
      setSubmitState("error");
      setSubmitError("Не удалось отправить заявку. Попробуйте еще раз.");
    }
  }

  return (
    <section id="enrollment" className={styles.section}>
      <div className={styles.container}>
        <div className={styles.head}>
          <div>
            <h2 className={styles.title}>Ближайшие курсы для записи</h2>
            <p className={styles.text}>
              Выберите курс как товар: смотрите стоимость, даты старта и сразу
              оформляйте заявку на оплату через сайт.
            </p>
          </div>

          <label className={styles.sort}>
            <span>Сортировка</span>
            <select
              value={sortBy}
              onChange={(event) => setSortBy(event.target.value as "date" | "cheap" | "expensive")}
            >
              <option value="date">По дате старта</option>
              <option value="cheap">Сначала дешевле</option>
              <option value="expensive">Сначала дороже</option>
            </select>
          </label>
        </div>

        <div className={styles.grid}>
          {sortedProducts.map((item) => (
            <article className={styles.card} key={item.id}>
              <div className={styles.imageWrap}>
                <img src={item.image} alt={item.title} loading="lazy" />
                {item.oldPrice && <span className={styles.badge}>Спеццена</span>}
              </div>

              <div className={styles.cardBody}>
                <p className={styles.meta}>{item.format} • {item.duration}</p>
                <h3 className={styles.cardTitle}>{item.title}</h3>
                <p className={styles.start}>Старт: {item.startDate}</p>

                <div className={styles.priceRow}>
                  <p className={styles.price}>{formatPrice(item.price)} руб.</p>
                  {item.oldPrice && <p className={styles.oldPrice}>{formatPrice(item.oldPrice)} руб.</p>}
                </div>

                <p className={styles.spots}>Осталось мест: {item.spots}</p>

                <ul className={styles.features}>
                  {item.features.map((feature) => (
                    <li key={feature}>{feature}</li>
                  ))}
                </ul>

                <div className={styles.actions}>
                  <button
                    type="button"
                    className={styles.buyButton}
                    onClick={() => {
                      setActiveProduct(item);
                      setSubmitState("idle");
                      setSubmitError("");
                    }}
                  >
                    Оформить курс
                  </button>
                  <a href="#formats" className={styles.linkButton}>Подробнее</a>
                </div>
              </div>
            </article>
          ))}
        </div>
      </div>

      {activeProduct && (
        <div className={styles.modalOverlay} onClick={() => setActiveProduct(null)}>
          <div className={styles.modalCard} onClick={(event) => event.stopPropagation()}>
            <button
              type="button"
              className={styles.modalClose}
              onClick={() => setActiveProduct(null)}
              aria-label="Закрыть окно"
            >
              ×
            </button>

            <h3 className={styles.modalTitle}>Оформление курса</h3>
            <p className={styles.modalSubtitle}>{activeProduct.title}</p>
            <p className={styles.modalPrice}>{formatPrice(activeProduct.price)} руб.</p>

            <form className={styles.modalForm} onSubmit={handleCheckout}>
              <label className={styles.field}>
                <span>Имя</span>
                <input name="name" type="text" placeholder="Как к вам обращаться" />
              </label>

              <label className={styles.field}>
                <span>Телефон</span>
                <input name="phone" type="tel" placeholder="+7 (___) ___-__-__" required />
              </label>

              <label className={styles.field}>
                <span>Комментарий</span>
                <textarea name="comment" rows={3} placeholder="Удобное время для звонка" />
              </label>

              <button className={styles.submitButton} type="submit" disabled={submitState === "sending"}>
                {submitState === "sending" ? "Отправляем..." : "Перейти к оплате"}
              </button>

              {submitState === "success" && (
                <p className={styles.successText}>
                  Заявка принята. Менеджер свяжется с вами для подтверждения и оплаты.
                </p>
              )}

              {submitState === "error" && <p className={styles.errorText}>{submitError}</p>}
            </form>
          </div>
        </div>
      )}
    </section>
  );
}
