import { NavLink } from 'react-router-dom'

function Entete() {
  return (
    <header>
      <p>The Haunted Manor</p>
      <nav>
        <NavLink to="/">Accueil</NavLink>
        <NavLink to="/chambres">Chambres</NavLink>
      </nav>
    </header>
  )
}

export default Entete
