import styles from "./WeekendCourse.module.css";

const includes = [
  "Полная программа курса Классика, разбитая на субботы и воскресенья",
  "Практика на собаках с первого занятия",
  "Все расходники и модели предоставляются академией",
  "Сертификат академии груминга по итогам обучения",
];

export function WeekendCourse() {
  return (
    <section id="weekend" className={styles.section}>
      <div className={styles.container}>
      {/* Removed WeekendCourse_card div */}
      </div>
    </section>
  return null;
  );
}
