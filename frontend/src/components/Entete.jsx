import { Link, NavLink } from 'react-router-dom'

function Entete() {
  return (
    <header>
      <Link to="/">
        <img src="/images/logo.png" alt="The Haunted Manor, retour à l'accueil" width="120" />
      </Link>

      <nav>
        <NavLink to="/">Accueil</NavLink>
        <NavLink to="/chambres">Chambres</NavLink>
      </nav>
    </header>
  )
}

export default Entete
