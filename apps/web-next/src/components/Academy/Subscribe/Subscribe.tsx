"use client";

import { useState } from "react";
import styles from "./Subscribe.module.css";

export function Subscribe() {
  const [phone, setPhone] = useState("");
  const [status, setStatus] = useState<"idle" | "sending" | "done" | "error">("idle");

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!phone.trim()) return;
    setStatus("sending");
    try {
      const res = await fetch("/api/telegram-lead", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ phone, message: "Подписка — разбор ошибок" }),
      });
      setStatus(res.ok ? "done" : "error");
    } catch {
      setStatus("error");
    }
  };

  return (
    <section id="subscribe" className={styles.section}>
      <div className={styles.container}>
        <div className={styles.content}>
<<<<<<< HEAD
          <p className={styles.kicker}>БЕСПЛАТНО</p>
          <h2 className={styles.title}>Получите разбор ошибок грумеров</h2>
          <p className={styles.desc}>
            Материалы, которые помогут быстрее начать работать и избежать типичных ошибок в профессии.
          </p>
          {status === "done" ? (
            <p className={styles.success}>
              Отправлено! Мы свяжемся с вами в ближайшее время.
=======
          <h2 className={styles.title}>Будьте в курсе событий академии</h2>
          <p className={styles.desc}>
            Подпишитесь, чтобы первыми узнавать<br />
            о новых курсах, датах обучения, мастер-классах<br />
            и мероприятиях академии BONSHERY<br /><br />
            Для подписчиков доступна приоритетная запись на обучение
          </p>
          {status === "done" ? (
            <p className={styles.success}>
              Спасибо за подписку! Теперь вы будете получать только важные обновления и анонсы.
>>>>>>> 6392f78 (fix: центрирование фото в блоке FounderSection)
            </p>
          ) : (
            <form className={styles.form} onSubmit={handleSubmit}>
              <input
                className={styles.input}
                type="tel"
                placeholder="Ваш телефон"
                value={phone}
                onChange={(e) => setPhone(e.target.value)}
                required
              />
              <button
                className={styles.btn}
                type="submit"
                disabled={status === "sending"}
              >
<<<<<<< HEAD
                {status === "sending" ? "Отправляем…" : "Получить"}
=======
                {status === "sending" ? "Отправляем…" : "Подписаться"}
>>>>>>> 6392f78 (fix: центрирование фото в блоке FounderSection)
              </button>
            </form>
          )}
          {status === "error" && (
            <p className={styles.errorMsg}>Ошибка. Попробуйте ещё раз.</p>
          )}
<<<<<<< HEAD
          <p className={styles.note}>Нажимая «Получить», вы соглашаетесь с политикой конфиденциальности.</p>
=======
          <p className={styles.note}>Только важные обновления и анонсы</p>
>>>>>>> 6392f78 (fix: центрирование фото в блоке FounderSection)
        </div>
      </div>
    </section>
  );
}
