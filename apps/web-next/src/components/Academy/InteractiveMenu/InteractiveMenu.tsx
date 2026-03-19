"use client";

import { useState } from "react";
import styles from "./InteractiveMenu.module.css";

const menuItems = [
  { label: "Основной курс", href: "#course" },
  { label: "Форматы", href: "#formats" },
  { label: "Ближайшие группы", href: "#enrollment" },
  { label: "Что входит", href: "#includes" },
  { label: "FAQ", href: "#faq" },
  { label: "Выходной курс", href: "#weekend" },
  { label: "Наши выпускники", href: "#graduates" },
  { label: "Контакты", href: "#contacts" },
];

export function InteractiveMenu() {
  const [open, setOpen] = useState(false);

  return (
    <>
      <aside className={styles.sidebar}>
        <div className={styles.stickyBox}>
          <h3 className={styles.title}>Навигация</h3>
          <nav>
            <ul className={styles.list}>
              {menuItems.map((item) => (
                <li key={item.href}>
                  <a href={item.href}>{item.label}</a>
                </li>
              ))}
            </ul>
          </nav>

          <div className={styles.infoBox}>
            <p className={styles.infoTitle}>Ближайшие группы</p>
            <p>Базовый: 12-16 марта</p>
            <p>Классика: 12-18 марта</p>
            <p>Интенсив: 12-21 марта</p>
            <a href="#enrollment">Смотреть все даты</a>
          </div>
        </div>
      </aside>

      <button
        type="button"
        className={styles.mobileToggle}
        onClick={() => setOpen((prev) => !prev)}
        aria-expanded={open}
        aria-controls="academy-mobile-menu"
      >
        {open ? "Закрыть меню" : "Открыть меню"}
      </button>

      <div id="academy-mobile-menu" className={`${styles.mobilePanel} ${open ? styles.mobilePanelOpen : ""}`}>
        <div className={styles.mobileHeader}>
          <p>Быстрая навигация</p>
          <button type="button" onClick={() => setOpen(false)}>
            Закрыть
          </button>
        </div>
        <ul className={styles.mobileList}>
          {menuItems.map((item) => (
            <li key={item.href}>
              <a href={item.href} onClick={() => setOpen(false)}>
                {item.label}
              </a>
            </li>
          ))}
        </ul>
      </div>
    </>
  );
}
