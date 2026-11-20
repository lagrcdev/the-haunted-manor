import { Link } from 'react-router-dom'

function PiedDePage() {
  return (
    <footer>
      <div className="pied-colonnes">
        <section>
          <h2>The Haunted Manor</h2>
          <p>
            12 allée du Brouillard<br />
            00000 Brumeville
          </p>
          <p>contact@haunted-manor.example</p>
        </section>

        <nav aria-label="Navigation du pied de page">
          <h2>Navigation</h2>
          <Link to="/">Accueil</Link>
          <Link to="/chambres">Chambres</Link>
          <Link to="/livre-d-or">Livre d'or</Link>
        </nav>

        <div>
          <nav aria-label="Informations légales">
            <h2>Informations légales</h2>
            <Link to="/mentions-legales">Mentions légales</Link>
            <Link to="/confidentialite">Politique de confidentialité</Link>
          </nav>
          <p className="telephone">Tél. : 06 00 00 00 00</p>
        </div>
      </div>

      <p className="droits">© {new Date().getFullYear()} The Haunted Manor - Projet fictif pour le titre DWWM</p>
    </footer>
  )
}

export default PiedDePage
