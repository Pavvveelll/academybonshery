import { Header } from "@/components/Academy/Header/Header";
import { QuickChoice } from "@/components/Academy/QuickChoice/QuickChoice";
import { AboutAcademy } from "@/components/Academy/AboutAcademy/AboutAcademy";
import { FounderSection } from "@/components/Academy/FounderSection/FounderSection";
import { InternationalSection } from "@/components/Academy/InternationalSection/InternationalSection";
import { GrowthPrograms } from "@/components/Academy/GrowthPrograms/GrowthPrograms";
import { Formats } from "@/components/Academy/Formats/Formats";
import { Enrollment } from "@/components/Academy/Enrollment/Enrollment";
import { CTASection } from "@/components/Academy/CTASection/CTASection";
import { GraduateVideo } from "@/components/Academy/GraduateVideo/GraduateVideo";
import { OpenDoors } from "@/components/Academy/OpenDoors/OpenDoors";
import { FranchiseSection } from "@/components/Academy/FranchiseSection/FranchiseSection";
import { FinalCTA } from "@/components/Academy/FinalCTA/FinalCTA";
import { FAQ } from "@/components/Academy/FAQ/FAQ";
import { WeekendCourse } from "@/components/Academy/WeekendCourse/WeekendCourse";
import { Contacts } from "@/components/Academy/Contacts/Contacts";
import { Subscribe } from "@/components/Academy/Subscribe/Subscribe";
import { InteractiveMenu } from "@/components/Academy/InteractiveMenu/InteractiveMenu";
import { Footer } from "@/components/Academy/Footer/Footer";
import { CookieBanner } from "@/components/Academy/CookieBanner/CookieBanner";
import styles from "./page.module.css";

export default function Home() {
  return (
    <>
      <CookieBanner />
      <div className={styles.pageLayout}>
        <InteractiveMenu />
        <div className={styles.mainContent}>
          <Header />
          <main className={styles.main}>
            <QuickChoice />
            <AboutAcademy />
            <FounderSection />
            <InternationalSection />
            <Formats />
            <Enrollment />
            <CTASection />
            <GraduateVideo />
            <GrowthPrograms />
            <OpenDoors />
            <FranchiseSection />
            <FinalCTA />
            <FAQ />
            <WeekendCourse />
            <Contacts />
            <Subscribe />
          </main>
        </div>
      </div>
      <Footer />
    </>
  );
}
