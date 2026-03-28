import type { Metadata } from "next";
import localFont from "next/font/local";
import { IBM_Plex_Sans, Geist_Mono } from "next/font/google";
import Script from "next/script";
import "./globals.css";

const bodyFont = IBM_Plex_Sans({
  variable: "--font-geist-sans",
  subsets: ["latin", "cyrillic"],
  weight: ["400", "500", "600", "700"],
});

const displayFont = localFont({
  src: "../../public/fonts/vetrino.otf",
  variable: "--font-display",
  display: "swap",
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin", "cyrillic"],
});

export const metadata: Metadata = {
  title: "Bonshery Grooming School",
  description: "Современный сайт курсов груминга Bonshery",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html
      lang="ru"
      className={`${bodyFont.variable} ${displayFont.variable} ${geistMono.variable} h-full antialiased`}
    >
      <body className="min-h-full flex flex-col">
        {children}
        {/* Яндекс.Метрика теперь подключается через app/head.tsx */}
        <noscript>
          <div>
            <img
              src="https://mc.yandex.ru/watch/8132020"
              style={{ position: "absolute", left: "-9999px" }}
              alt=""
            />
          </div>
        </noscript>
      </body>
    </html>
  );
}
