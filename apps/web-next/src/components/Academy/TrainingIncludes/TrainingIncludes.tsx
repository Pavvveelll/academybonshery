import styles from "./TrainingIncludes.module.css";

const graduateBenefits = [
  "возможность купить инструменты в школе по особым ценам",
  "портфолио работ с учебы",
  "методическое пособие для самостоятельной работы",
  "заочный курс Ветеринария для грумеров с сертификатом",
  "доступ к телеграм-каналу с полезными материалами",
];

export function TrainingIncludes() {
  return (
    <section id="includes" className={styles.section}>
      <div className={styles.container}>
        <h2 className={styles.title}>Что включает в себя обучение</h2>
        <p className={styles.subtitle}>
          По материалам страницы Основного курса: обучение состоит из теоретического блока и
          ежедневной практики в Академии.
        </p>

        <div className={styles.columns}>
          <article className={styles.card}>
            <h3>1. Теоретический блок</h3>
            <p>
              После оплаты студент получает авторские материалы на электронную почту и проходит
              предобучение. Это помогает приехать в школу подготовленным и быстрее перейти к
              практике.
            </p>
          </article>

          <article className={styles.card}>
            <h3>2. Практический блок</h3>
            <p>
              Ежедневная отработка навыков на моделях собак с первого дня очной части.
              Индивидуальная модель на ученика и фокус на реальной салонной работе.
            </p>
          </article>
        </div>

        <div className={styles.panel}>
          <h3>Выпускники получают:</h3>
          <ul>
            {graduateBenefits.map((item) => (
              <li key={item}>{item}</li>
            ))}
          </ul>
        </div>
      </div>
    </section>
  );
}
