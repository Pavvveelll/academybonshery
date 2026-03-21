"use client";

import { useEffect, useState } from "react";
import styles from "./CookieBanner.module.css";

export function CookieBanner() {
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    if (!localStorage.getItem("cookie_accepted")) {
      setVisible(true);
    }
  }, []);

  function accept() {
    localStorage.setItem("cookie_accepted", "1");
    setVisible(false);
  }

  if (!visible) return null;

  return (
    <div className={styles.overlay} role="dialog" aria-modal="true" aria-label="Согласие с использованием cookies">
      <div className={styles.banner}>
        <p className={styles.text}>
          Мы используем cookies и обрабатываем персональные данные
          в соответствии с законодательством РФ.
          Продолжая использовать сайт, вы соглашаетесь с условиями.
        </p>
        <div className={styles.actions}>
          <button type="button" className={styles.btnPrimary} onClick={accept}>
            Принять
          </button>
          <a
            href="https://www.petsgroomer.ru/soglashenie/"
            target="_blank"
            rel="noreferrer"
            className={styles.btnSecondary}
          >
            Ознакомиться
          </a>
        </div>
      </div>
    </div>
  );
}
