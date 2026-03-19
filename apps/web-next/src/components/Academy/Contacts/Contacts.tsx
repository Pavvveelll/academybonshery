import styles from "./Contacts.module.css";
import { ContactForm } from "./ContactForm";

export function Contacts() {
  return (
    <section id="contacts" className={styles.section}>
      <div className={styles.container}>
        <div className={styles.card}>
          <h2 className={styles.title}>Остались вопросы? Свяжитесь с нами</h2>
          <p className={styles.text}>
            Для записи на курс и консультации: 8 (499) 994-01-40, 8 (925) 889-99-63
            (Viber, WhatsApp, Telegram), e-mail: school@petsgroomer.ru.
          </p>
          <ContactForm />
          <div className={styles.actions}>
            <a
              href="tel:+74999940140"
              className={`${styles.primary} ${styles.tooltipTarget}`}
              data-tooltip="Звонок в 1 клик"
            >
              Позвонить
            </a>
            <a
              href="mailto:school@petsgroomer.ru"
              className={`${styles.secondary} ${styles.tooltipTarget}`}
              data-tooltip="Откроется ваш почтовый клиент"
            >
              Написать на почту
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}
