export default function Head() {
  return (
    <>
      <title>Bonshery Grooming School</title>
      <meta name="description" content="Современный сайт курсов груминга Bonshery" />
      <script
        id="ym-init"
        dangerouslySetInnerHTML={{
          __html: `(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");ym(8132020, "init", {clickmap:true,trackLinks:true,accurateTrackBounce:true,webvisor:true});`
        }}
      />
    </>
  );
}
