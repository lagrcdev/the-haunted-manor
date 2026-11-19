function Confidentialite() {
  return (
    <section className="confidentialite">
      <h1>Politique de confidentialité</h1>

      <h2>Données personnelles</h2>
      <p>Quand vous créez un compte, nous enregistrons :</p>
      <ul>
        <li>votre nom et votre adresse e-mail ;</li>
        <li>votre mot de passe, jamais en clair : il est haché, c'est-à-dire transformé en une suite de caractères qu'on ne peut pas relire ;</li>
        <li>vos réservations (chambre, dates, prix) ;</li>
        <li>vos avis du livre d'or (votre nom, la note, le texte et la date).</li>
      </ul>
      <p>
        Ces données servent uniquement à gérer votre compte, vos réservations et le
        livre d'or. Elles ne sont ni vendues, ni transmises à d'autres sociétés.
        Vous donnez votre accord en cochant la case au moment de l'inscription.
      </p>
      <p>
        Elles sont gardées tant que votre compte existe. Vous pouvez demander leur
        suppression à tout moment.
      </p>

      <h2>Vos droits</h2>
      <p>
        Vous pouvez demander à voir vos données, à les corriger ou à les faire
        supprimer. Pour cela, écrivez à contact@haunted-manor.example. Si vous pensez
        que vos droits ne sont pas respectés, vous pouvez aussi saisir la CNIL
        (Commission nationale de l'informatique et des libertés).
      </p>

      <h2>Cookies</h2>
      <p>
        Le site dépose un seul cookie, nécessaire à la connexion : il garde votre
        session ouverte et disparaît quand vous fermez le navigateur. Il n'y a ni
        publicité, ni suivi, ni outil de mesure d'audience. Les polices sont
        hébergées sur le site : aucune donnée n'est envoyée à un service extérieur.
      </p>
    </section>
  )
}

export default Confidentialite
