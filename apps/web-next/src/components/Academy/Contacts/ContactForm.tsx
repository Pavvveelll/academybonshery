"use client";

import { FormEvent, useState } from "react";
import styles from "./Contacts.module.css";

type SubmitState = "idle" | "sending" | "success" | "error";

export function ContactForm() {
  const [state, setState] = useState<SubmitState>("idle");
  const [errorText, setErrorText] = useState("");

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();

    const form = event.currentTarget;
    const formData = new FormData(form);

    const name = String(formData.get("name") ?? "").trim();
    const phone = String(formData.get("phone") ?? "").trim();
    const message = String(formData.get("message") ?? "").trim();

    if (!phone) {
      setState("error");
      setErrorText("Укажите номер телефона.");
      return;
    }

    setState("sending");
    setErrorText("");

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

      setState("success");
      form.reset();
    } catch {
      setState("error");
      setErrorText("Не удалось отправить заявку. Попробуйте еще раз.");
    }
  }

  return (
    <form className={styles.form} onSubmit={handleSubmit}>
      <div className={styles.formGrid}>
        <label className={styles.field}>
          <span>Имя</span>
          <input name="name" type="text" placeholder="Как к вам обращаться" />
        </label>

        <label className={styles.field}>
          <span>Телефон</span>
          <input name="phone" type="tel" placeholder="+7 (___) ___-__-__" required />
        </label>
      </div>

      <label className={styles.field}>
        <span>Комментарий</span>
        <textarea
          name="message"
          rows={4}
          placeholder="Удобное время для звонка или вопрос по курсу"
        />
      </label>

      <div className={styles.formActions}>
        <button
          className={`${styles.submitButton} ${styles.tooltipTarget}`}
          data-tooltip="Отправка заявки в Telegram"
          type="submit"
          disabled={state === "sending"}
        >
          {state === "sending" ? "Отправляем..." : "Отправить заявку"}
        </button>

        {state === "success" && (
          <p className={styles.successText}>Спасибо! Мы скоро свяжемся с вами.</p>
        )}

        {state === "error" && <p className={styles.errorText}>{errorText}</p>}
      </div>
    </form>
  );
}
