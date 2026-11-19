import { Link } from 'react-router-dom'

function PiedDePage() {
  return (
    <footer>
      <p>The Haunted Manor - Projet fictif pour le titre DWWM</p>
      <nav aria-label="Informations légales">
        <Link to="/mentions-legales">Mentions légales</Link>
        <Link to="/confidentialite">Politique de confidentialité</Link>
      </nav>

    </footer>
  )
}

export default PiedDePage
