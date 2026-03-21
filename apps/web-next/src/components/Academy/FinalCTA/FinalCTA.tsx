import styles from "./FinalCTA.module.css";

export function FinalCTA() {
  return (
    <section id="final-cta" className={styles.section}>
      <div className={styles.container}>
        <h2 className={styles.title}>
          Начните свой путь в профессии или выйдите на новый уровень
        </h2>
        <p className={styles.text}>
          Вы можете продолжать откладывать или сделать первый шаг уже сейчас.
        </p>
        <div className={styles.actions}>
          <a href="#quick-choice" className={styles.btnPrimary}>Выбрать обучение</a>
          <a href="#contacts" className={styles.btnSecondary}>Получить консультацию</a>
        </div>
      </div>
    </section>
  );
}
