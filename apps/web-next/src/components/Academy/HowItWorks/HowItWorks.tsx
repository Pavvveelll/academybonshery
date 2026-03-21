import styles from "./HowItWorks.module.css";

const steps = [
  { num: "01", title: "Практика с первых дней", desc: "Вы работаете с реальными собаками уже с первого дня обучения, а не смотрите на то, как это делают другие." },
  { num: "02", title: "Работа с моделями", desc: "Индивидуальная модель на каждого ученика — вы отрабатываете навык в полном объёме, не делясь с группой." },
  { num: "03", title: "Обратная связь", desc: "Преподаватель разбирает вашу работу после каждого занятия: что получилось, что нужно улучшить и почему." },
  { num: "04", title: "Поддержка после выпуска", desc: "После курса вы не остаётесь одни: доступ к материалам, телеграм-канал и консультации куратора." },
];

export function HowItWorks() {
  return (
    <section id="how-it-works" className={styles.section}>
      <div className={styles.container}>
        <p className={styles.kicker}>ПРОЦЕСС</p>
        <h2 className={styles.title}>Как проходит обучение</h2>
        <div className={styles.grid}>
          {steps.map((step) => (
            <article key={step.num} className={styles.step}>
              <span className={styles.stepNum}>{step.num}</span>
              <h3 className={styles.stepTitle}>{step.title}</h3>
              <p className={styles.stepDesc}>{step.desc}</p>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
