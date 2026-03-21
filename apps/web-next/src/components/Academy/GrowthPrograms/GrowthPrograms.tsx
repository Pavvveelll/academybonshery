import styles from "./GrowthPrograms.module.css";

const programs = [
  {
    title: "От мастера к педагогу",
    desc: "Переход в преподавание",
  },
  {
    title: "Нейросети для мастера",
    desc: "Упрощение работы и рост дохода",
  },
  {
    title: "Нейросети для владельцев",
    desc: "Управление и масштабирование",
  },
  {
    title: "Нейросети для педагогов",
    desc: "Создание и развитие обучения",
  },
];

export function GrowthPrograms() {
  return (
    <section id="growth" className={styles.section}>
      <div className={styles.container}>
        <p className={styles.kicker}>ПРОГРАММЫ РОСТА</p>
        <h2 className={styles.title}>Развитие внутри профессии</h2>
        <div className={styles.grid}>
          {programs.map((item) => (
            <article key={item.title} className={styles.card}>
              <h3 className={styles.cardTitle}>{item.title}</h3>
              <p className={styles.cardDesc}>{item.desc}</p>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
