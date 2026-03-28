"use client";

import { useEffect, useState } from "react";
import styles from "./InteractiveMenu.module.css";

const menuItems = [
  { label: "О академии", href: "#about" },
  { label: "Основатель", href: "#founder" },
  { label: "Международный опыт", href: "#international" },
  { label: "Курсы с нуля", href: "#formats" },
  { label: "Ближайшие наборы", href: "#enrollment" },
  { label: "Программы роста", href: "#growth" },
  { label: "Отзывы", href: "#graduates" },
  { label: "День открытых дверей", href: "#open-doors" },
  { label: "Франшиза", href: "#franchise" },
  { label: "Контакты", href: "#contacts" },
];

export function InteractiveMenu() {
  const [open, setOpen] = useState(false);
  const [activeHref, setActiveHref] = useState("#about");

  function handleNavClick(href: string, shouldCloseMobile = false) {
    const id = href.replace("#", "");
    const target = document.getElementById(id);
    if (!target) {
      return;
    }

    target.scrollIntoView({ behavior: "smooth", block: "start" });
    setActiveHref(href);
    window.history.replaceState(null, "", href);

    if (shouldCloseMobile) {
      setOpen(false);
    }
  }

  function handleScrollTop() {
    window.scrollTo({ top: 0, behavior: "smooth" });
    setActiveHref("#about");
    setOpen(false);
  }

  useEffect(() => {
    const sections = menuItems
      .map((item) => {
        const id = item.href.replace("#", "");
        return document.getElementById(id);
      })
      .filter((section): section is HTMLElement => Boolean(section));

    if (sections.length === 0) {
      return;
    }

    const observer = new IntersectionObserver(
      (entries) => {
        const visible = entries
          .filter((entry) => entry.isIntersecting)
          .sort((a, b) => b.intersectionRatio - a.intersectionRatio);

        if (visible.length > 0) {
          setActiveHref(`#${visible[0].target.id}`);
        }
      },
      {
        rootMargin: "-20% 0px -62% 0px",
        threshold: [0.2, 0.45, 0.7],
      },
    );

    sections.forEach((section) => observer.observe(section));

    return () => {
      observer.disconnect();
    };
  }, []);

  useEffect(() => {
    function onHashChange() {
      const hash = window.location.hash;
      if (hash && menuItems.some((item) => item.href === hash)) {
        setActiveHref(hash);
      }
    }

    onHashChange();
    window.addEventListener("hashchange", onHashChange);

    return () => {
      window.removeEventListener("hashchange", onHashChange);
    };
  }, []);

  return (
    <>
      <aside className={styles.sidebar}>
        <div className={styles.stickyBox}>
          <h3 className={styles.title}>Навигация</h3>
          <nav aria-label="Навигация по секциям страницы">
            <ul className={styles.list}>
              {menuItems.map((item) => (
                <li key={item.href}>
                  <a
                    href={item.href}
                    onClick={(event) => {
                      event.preventDefault();
                      handleNavClick(item.href);
                    }}
                    className={activeHref === item.href ? styles.activeLink : ""}
                    aria-current={activeHref === item.href ? "page" : undefined}
                  >
                    {item.label}
                  </a>
                </li>
              ))}
            </ul>
          </nav>

          <div className={styles.infoBox}>
            <p className={styles.infoTitle}>Быстрые действия</p>
            <a href="#enrollment" onClick={(event) => { event.preventDefault(); handleNavClick("#enrollment"); }}>
              Открыть ближайшие наборы
            </a>
            <a href="#contacts" onClick={(event) => { event.preventDefault(); handleNavClick("#contacts"); }}>
              Получить консультацию
            </a>
            <a href="tel:+74999940140">Позвонить: 8 (499) 994-01-40</a>
          </div>

          <button type="button" className={styles.toTopButton} onClick={handleScrollTop}>
            Наверх
          </button>
        </div>
      </aside>

      <button
        type="button"
        className={styles.mobileToggle}
        onClick={() => setOpen((prev) => !prev)}
        aria-expanded={open}
        aria-controls="academy-mobile-menu"
      >
        {open ? "Закрыть меню" : "Открыть меню"}
      </button>

      <div id="academy-mobile-menu" className={`${styles.mobilePanel} ${open ? styles.mobilePanelOpen : ""}`}>
        <div className={styles.mobileHeader}>
          <p>Быстрая навигация</p>
          <button type="button" onClick={() => setOpen(false)}>
            Закрыть
          </button>
        </div>
        <ul className={styles.mobileList}>
          {menuItems.map((item) => (
            <li key={item.href}>
              <a
                href={item.href}
                onClick={(event) => {
                  event.preventDefault();
                  handleNavClick(item.href, true);
                }}
                className={activeHref === item.href ? styles.mobileActiveLink : ""}
                aria-current={activeHref === item.href ? "page" : undefined}
              >
                {item.label}
              </a>
            </li>
          ))}
        </ul>

        <button type="button" className={styles.mobileTopButton} onClick={handleScrollTop}>
          Наверх
        </button>
      </div>
    </>
  );
}
