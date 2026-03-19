import { Header } from "@/components/Academy/Header/Header";
import { InteractiveMenu } from "@/components/Academy/InteractiveMenu/InteractiveMenu";
import { CourseIntro } from "@/components/Academy/CourseIntro/CourseIntro";
import { Formats } from "@/components/Academy/Formats/Formats";
import { Enrollment } from "@/components/Academy/Enrollment/Enrollment";
import { TrainingIncludes } from "@/components/Academy/TrainingIncludes/TrainingIncludes";
import { FAQ } from "@/components/Academy/FAQ/FAQ";
import { WeekendCourse } from "@/components/Academy/WeekendCourse/WeekendCourse";
import { GraduateVideo } from "@/components/Academy/GraduateVideo/GraduateVideo";
import { Contacts } from "@/components/Academy/Contacts/Contacts";
import { Footer } from "@/components/Academy/Footer/Footer";
import styles from "./page.module.css";

export default function Home() {
  return (
    <>
      <Header />
      <div className={styles.layout}>
        <InteractiveMenu />

        <main className={styles.main}>
          <div className={styles.sectionCard}><CourseIntro /></div>
          <div className={styles.sectionCard}><Formats /></div>
          <div className={styles.sectionCard}><Enrollment /></div>
          <div className={styles.sectionCard}><TrainingIncludes /></div>
          <div className={styles.sectionCard}><FAQ /></div>
          <div className={styles.sectionCard}><WeekendCourse /></div>
          <div className={styles.sectionCard}><GraduateVideo /></div>
          <div className={styles.sectionCard}><Contacts /></div>
        </main>
      </div>
      <Footer />
    </>
  );
}
