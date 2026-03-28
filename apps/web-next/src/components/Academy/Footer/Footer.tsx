import styles from "./Footer.module.css";
import { LogoTextSvg } from "./LogoTextSvg";

const navLinks = [
  { label: "О академии", href: "#about" },
  { label: "Международный опыт", href: "#international" },
  { label: "Курсы с нуля", href: "#formats" },
  { label: "Ближайшие наборы", href: "#enrollment" },
  { label: "Программы роста", href: "#growth" },
  { label: "День открытых дверей", href: "#open-doors" },
  { label: "Франшиза", href: "#franchise" },
  { label: "FAQ", href: "#faq" },
  { label: "Контакты", href: "#contacts" },
  { label: "Вакансии", href: "https://www.petsgroomer.ru/vacancy_rabota_grumerom/" },
  { label: "Сведения об организации", href: "https://www.petsgroomer.ru/sveden/" },
];

const socialLinks = [
  {
    label: "ВКонтакте",
    href: "https://vk.com/club_bonshery",
    icon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M12.785 16.241s.288-.032.436-.194c.136-.148.132-.427.132-.427s-.02-1.304.586-1.496c.598-.19 1.365 1.26 2.179 1.815.615.42 1.082.328 1.082.328l2.175-.03s1.137-.071.598-1.105c-.044-.083-.314-.663-1.618-1.875-1.366-1.268-1.183-1.063.462-3.256.999-1.338 1.4-2.152 1.274-2.501-.12-.332-.854-.244-.854-.244l-2.447.015s-.182-.025-.316.059c-.132.082-.217.272-.217.272s-.387 1.098-.904 2.031c-1.088 1.874-1.523 1.972-1.701 1.857-.413-.27-.31-1.08-.31-1.656 0-1.8.272-2.55-.529-2.744-.265-.064-.46-.106-1.138-.113-.87-.009-1.606.003-2.021.209-.277.136-.491.44-.361.457.162.022.529.1.724.369.25.344.241 1.116.241 1.116s.144 2.12-.335 2.382c-.328.179-.778-.186-1.744-1.853-.495-.907-.87-1.912-.87-1.912s-.072-.183-.202-.282c-.157-.12-.376-.158-.376-.158l-2.324.015s-.349.01-.477.164c-.114.137-.009.42-.009.42s1.819 4.27 3.877 6.421c1.888 1.973 4.031 1.842 4.031 1.842h.972z" />
      </svg>
    ),
  },
  {
    label: "Telegram",
    href: "https://t.me/bonshery_groom",
    icon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.96 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
      </svg>
    ),
  },
  {
    label: "MAX",
    href: "https://max.ru/bonshery",
    icon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.5 14.5h-2.25L12 13.25 9.75 16.5H7.5L10.875 12 7.5 7.5h2.25L12 10.75 14.25 7.5H16.5L13.125 12 16.5 16.5z" />
      </svg>
    ),
  },
];

export function Footer() {
  return (
    <footer className={styles.footer}>
      <div className={styles.glow} aria-hidden="true" />

      <div className={styles.inner}>
        {/* Колонка 1 — бренд и контакты */}
        <div className={styles.brand}>
          <div className={styles.brandCluster}>
              <LogoTextSvg className={styles.logoText} />
              <p className={styles.brandSub}>Основана в 2007 году</p>
          </div>

          <ul className={styles.contacts}>
            <li>
              <span className={styles.contactIcon} aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
              </span>
              <span className={styles.contactText}>г. Москва, ул. Генерала Белобородова, дом 35/2</span>
            </li>
            <li>
              <span className={styles.contactIcon} aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                </svg>
              </span>
              <a href="tel:+74999940140" className={`${styles.contactLink} ${styles.tooltipTarget}`} data-tooltip="Позвонить в академию">
                8 (499) 994-01-40
              </a>
            </li>
            <li>
              <span className={styles.contactIcon} aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                </svg>
              </span>
              <a href="tel:+79258899963" className={`${styles.contactLink} ${styles.tooltipTarget}`} data-tooltip="Telegram / WhatsApp">
                8 (925) 889-99-63
              </a>
              <span className={styles.contactHint}>&nbsp;Telegram · WhatsApp</span>
            </li>
            <li>
              <span className={styles.contactIcon} aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                  <polyline points="22,6 12,13 2,6" />
                </svg>
              </span>
              <a href="mailto:school@petsgroomer.ru" className={`${styles.contactLink} ${styles.tooltipTarget}`} data-tooltip="Написать на e-mail">
                school@petsgroomer.ru
              </a>
            </li>
            <li>
              <span className={styles.contactIcon} aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <circle cx="12" cy="12" r="10" />
                  <polyline points="12 6 12 12 16 14" />
                </svg>
              </span>
              <span className={styles.contactText}>Ежедневно с 10:00 до 20:00</span>
            </li>
          </ul>
        </div>

        {/* Колонка 2 — навигация */}
        <nav className={styles.nav} aria-label="Разделы академии">
          <p className={styles.colTitle}>Разделы</p>
          <ul className={styles.navList}>
            {navLinks.map((link) => (
              <li key={link.href}>
                <a
                  href={link.href}
                  className={`${styles.navLink} ${styles.tooltipTarget}`}
                  data-tooltip={`Перейти: ${link.label}`}
                  {...(link.href.startsWith("http") ? { target: "_blank", rel: "noopener noreferrer" } : {})}
                >
                  {link.label}
                </a>
              </li>
            ))}
          </ul>
        </nav>

        {/* Колонка 3 — соцсети */}
        <div className={styles.social}>
          <p className={styles.colTitle}>Мы в социальных сетях</p>
          <ul className={styles.socialList}>
            {socialLinks.map((s) => (
              <li key={s.href}>
                <a
                  href={s.href}
                  className={`${styles.socialLink} ${styles.tooltipTarget}`}
                  data-tooltip={`Открыть: ${s.label}`}
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label={s.label}
                >
                  {s.icon}
                  <span>{s.label}</span>
                </a>
              </li>
            ))}
          </ul>

          {/* payments block удалён по запросу */}
        </div>
      </div>

      <div className={styles.bottom}>
        <div className={styles.bottomInner}>
          <p className={styles.copy}>© 2007–{new Date().getFullYear()} Академия груминга BONSHERY. Все права защищены.</p>
          <div className={styles.legalLinks}>
            <a href="/privacy" className={styles.legalLink}>Политика</a>
            <a href="/terms" className={styles.legalLink}>Соглашение</a>
            <a href="/offer" className={styles.legalLink}>Оферта</a>
          </div>
        </div>
      </div>
    </footer>
  );
}

