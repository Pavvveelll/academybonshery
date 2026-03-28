import styles from "./QuickChoice.module.css";

const choices = [
  {
    id: "course",
    title: "Курс с нуля",
    desc: "Освоить профессию и начать работать",
    href: "#enrollment",
  },
  {
    id: "masterclass",
    title: "Мастер-классы",
    desc: "Повышение уровня и качества работы",
    href: "#growth",
  },
  {
    id: "growth-program",
    title: "Программа роста",
    desc: "Профессиональное развитие и новые горизонты",
    href: "#growth-program",
  },
];

export function QuickChoice() {
  return (
    <section id="quick-choice" className={styles.section}>
      <div className={styles.container}>
        <h2 className={styles.title}>Выберите свой путь</h2>
        <div className={styles.grid}>
          {choices.map((item) => (
            <a key={item.id} href={item.href} className={styles.card}>
              <h3 className={styles.cardTitle}>{item.title}</h3>
              <p className={styles.cardDesc}>{item.desc}</p>
              <span className={styles.arrow}>→</span>
            </a>
          ))}
        </div>
      </div>
    </section>
  );
}
