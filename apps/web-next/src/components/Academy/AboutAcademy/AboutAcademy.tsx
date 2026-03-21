import styles from "./AboutAcademy.module.css";

export function AboutAcademy() {
  return (
    <section id="about" className={styles.section}>
      <div className={styles.container}>
        <p className={styles.kicker}>О АКАДЕМИИ</p>
        <h2 className={styles.title}>Система профессионального входа в индустрию</h2>
        <p className={styles.text}>
          BONSHERY — международная академия груминга, в которой формируется не просто навык,
          а профессиональное мышление специалиста. Здесь вы учитесь понимать, чувствовать
          и работать на уровне, за который готовы платить клиенты.
        </p>
        <p className={styles.text}>
          Мы создаём среду, в которой ученики растут быстрее, потому что находятся внутри
          реальной индустрии, а не учебной имитации.
        </p>
      </div>
    </section>
  );
}
