import styles from "./FranchiseSection.module.css";

const franchises = [
  {
    title: "Франшиза салона",
    desc: "Готовая модель бизнеса",
  },
  {
    title: "Франшиза академии",
    desc: "Система обучения и масштабирования",
  },
];

export function FranchiseSection() {
  return (
    <section id="franchise" className={styles.section}>
      <div className={styles.container}>
        <p className={styles.kicker}>ПАРТНЁРСТВО</p>
        <h2 className={styles.title}>Франшиза BONSHERY</h2>
        <div className={styles.grid}>
          {franchises.map((item) => (
            <article key={item.title} className={styles.card}>
              <h3 className={styles.cardTitle}>{item.title}</h3>
              <p className={styles.cardDesc}>{item.desc}</p>
              <a href="#contacts" className={styles.link}>Узнать подробнее →</a>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
