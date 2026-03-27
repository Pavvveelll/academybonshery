import styles from "./FinalCTA.module.css";

export function FinalCTA() {
  return (
    <section id="final-cta" className={styles.section}>
      <div className={styles.container}>
        <h2 className={styles.title}>
<<<<<<< HEAD
          Начните свой путь в профессии или выйдите на новый уровень
        </h2>
        <p className={styles.text}>
          Вы можете продолжать откладывать или сделать первый шаг уже сейчас.
        </p>
        <div className={styles.actions}>
          <a href="#quick-choice" className={styles.btnPrimary}>Выбрать обучение</a>
=======
          Вы можете остаться там, где вы сейчас<br />или начать новую профессию
        </h2>
        <p className={styles.text}>
          Решение всегда одно и то же:<br />начать или отложить
        </p>
        <div className={styles.actions}>
          <a href="#enrollment" className={styles.btnPrimary}>Записаться на обучение</a>
>>>>>>> 6392f78 (fix: центрирование фото в блоке FounderSection)
          <a href="#contacts" className={styles.btnSecondary}>Получить консультацию</a>
        </div>
      </div>
    </section>
  );
}
