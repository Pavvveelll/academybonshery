"use client";

import React, { useState } from "react";
import styles from "./AboutAcademy.module.css";

export default function AboutAccordion() {
  const [open, setOpen] = useState<string | null>("overview");
  const toggle = (id: string) => setOpen(open === id ? null : id);

  return (
    <div className={styles.accordion}>
      <div className={styles.accordionItem}>
        <button
          className={styles.accordionButton}
          onClick={() => toggle("overview")}
          aria-expanded={open === "overview"}
          aria-controls="mc-overview"
        >
          Обзор формата
          <span className={styles.accordionMarker}>{open === "overview" ? "−" : "+"}</span>
        </button>
        <div id="mc-overview" className={styles.accordionPanel} hidden={open !== "overview"}>
          <p className={styles.blockText}>Мастер-классы BONSHERY — это не базовое обучение. Это формат, в котором вы погружаетесь в профессиональную среду, работаете с сильными преподавателями и начинаете видеть уровень, к которому стоит стремиться.</p>
          <p className={styles.blockText}>Вы не просто изучаете новые техники — вы меняете качество своей работы и повышаете стоимость своих услуг.</p>
        </div>
      </div>

      <div className={styles.accordionItem}>
        <button
          className={styles.accordionButton}
          onClick={() => toggle("program")}
          aria-expanded={open === "program"}
          aria-controls="mc-program"
        >
          Что внутри программы
          <span className={styles.accordionMarker}>{open === "program" ? "−" : "+"}</span>
        </button>
        <div id="mc-program" className={styles.accordionPanel} hidden={open !== "program"}>
          <ul className={styles.masterList}>
            <li>повышение уровня работ</li>
            <li>новые техники и подходы</li>
            <li>разбор ошибок и cases</li>
            <li>понимание стандартов индустрии</li>
            <li>практика в реальных условиях</li>
          </ul>
        </div>
      </div>

      <div className={styles.accordionItem}>
        <button
          className={styles.accordionButton}
          onClick={() => toggle("for-who")}
          aria-expanded={open === "for-who"}
          aria-controls="mc-for-who"
        >
          Кому подойдёт
          <span className={styles.accordionMarker}>{open === "for-who" ? "−" : "+"}</span>
        </button>
        <div id="mc-for-who" className={styles.accordionPanel} hidden={open !== "for-who"}>
          <div className={styles.masterFor}>Кому подойдёт:<br/>— действующим мастерам<br/>— выпускникам курсов<br/>— тем, кто хочет расти<br/>— тем, кто хочет повысить чек</div>
        </div>
      </div>
    </div>
  );
}
