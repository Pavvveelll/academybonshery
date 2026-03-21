"use client";

import { FormEvent, useState } from "react";
import styles from "./OpenDoors.module.css";

type Status = "idle" | "sending" | "success" | "error";

export function OpenDoors() {
  const [status, setStatus] = useState<Status>("idle");

  async function handleSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    const form = e.currentTarget;
    const phone = String(new FormData(form).get("phone") ?? "").trim();
    if (!phone) return;

    setStatus("sending");
    try {
      const res = await fetch("/api/telegram-lead", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name: "День открытых дверей", phone, message: "Запись на день открытых дверей" }),
      });
      setStatus(res.ok ? "success" : "error");
      if (res.ok) form.reset();
    } catch {
      setStatus("error");
    }
  }

  return (
    <section id="open-doors" className={styles.section}>
      <div className={styles.container}>
        <div className={styles.card}>
          <div className={styles.content}>
            <p className={styles.kicker}>ДЕНЬ ОТКРЫТЫХ ДВЕРЕЙ</p>
            <h2 className={styles.title}>Познакомьтесь с профессией и примите решение без давления</h2>
            <p className={styles.accent}>🎁 Подарок при принятии решения — доступно участникам</p>
          </div>
          <form className={styles.form} onSubmit={handleSubmit}>
            <input
              name="phone"
              type="tel"
              placeholder="+7 (___) ___-__-__"
              required
              className={styles.input}
            />
            <button type="submit" className={styles.btn} disabled={status === "sending"}>
              {status === "sending" ? "Отправка..." : "Записаться"}
            </button>
            {status === "success" && <p className={styles.success}>Заявка принята, мы свяжемся с вами!</p>}
            {status === "error" && <p className={styles.error}>Произошла ошибка. Попробуйте позже.</p>}
          </form>
        </div>
      </div>
    </section>
  );
}
