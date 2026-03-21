import styles from "./CTASection.module.css";

export function CTASection() {
  return (
    <section id="cta" className={styles.section}>
      <div className={styles.container}>
        <h2 className={styles.title}>Выберите формат обучения</h2>
        <div className={styles.actions}>
          <a href="#enrollment" className={styles.btnPrimary}>Курс с нуля</a>
          <a href="#growth" className={styles.btnPrimary}>Мастер-классы</a>
          <a href="#contacts" className={styles.btnSecondary}>Консультация</a>
        </div>
      </div>
    </section>
  );
}
