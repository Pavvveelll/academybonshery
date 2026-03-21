import styles from "./AudienceSegments.module.css";

const segments = [
  {
    title: "Для новичков",
    text: "Если вы начинаете с нуля, получите понятную базу и пошаговый алгоритм работы с первыми клиентами.",
  },
  {
    title: "Для действующих мастеров",
    text: "Если уже работаете грумером, систематизируете технику и усилите качество услуг в сложных кейсах.",
  },
  {
    title: "Для тех, кто хочет сменить профессию",
    text: "Если хотите уйти из текущей сферы, получите практический навык и понятный маршрут перехода в новую профессию.",
  },
  {
    title: "Для тех, кто хочет повысить чек и уровень работ",
    text: "Отработаете коммерческие и породные схемы, чтобы уверенно поднимать стоимость услуг и удерживать клиентов.",
  },
  {
    title: "Для тех, кто хочет открыть свой салон или академию",
    text: "Поймете стандарты процессов, логику обучения и модель развития, чтобы масштабироваться в собственный проект.",
  },
];

export function AudienceSegments() {
  return (
    <section id="audience" className={styles.section}>
      <div className={styles.container}>
        <div className={styles.head}>
          <p className={styles.kicker}>Сегментация аудитории</p>
          <h2 className={styles.title}>Кому подходит обучение</h2>
          <p className={styles.subtitle}>
            Программа адаптируется под ваш текущий уровень и цель: от старта в профессии
            до роста в доходе и запуска собственного проекта.
          </p>
        </div>

        <div className={styles.grid}>
          {segments.map((segment) => (
            <article key={segment.title} className={styles.card}>
              <h3 className={styles.cardTitle}>{segment.title}</h3>
              <p className={styles.cardText}>{segment.text}</p>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
