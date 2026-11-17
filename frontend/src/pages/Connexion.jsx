import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { envoyer } from '../api.js'

function Connexion({ onConnexion }) {
  const navigate = useNavigate()
  const [email, setEmail] = useState('')
  const [motDePasse, setMotDePasse] = useState('')
  const [erreur, setErreur] = useState('')

  async function valider(evenement) {
    evenement.preventDefault()
    setErreur('')

    const resultat = await envoyer('POST', '/api/connexion', {
      email,
      mot_de_passe: motDePasse,
    })

    if (resultat.ok) {
      onConnexion(resultat.corps)
      navigate('/')
    } else {
      setErreur(resultat.corps.erreur)
    }
  }

  return (
    <section className="formulaire">
      <h1>Connexion</h1>
      <form onSubmit={valider}>
        <label htmlFor="email">Email</label>
        <input id="email" type="email" value={email} onChange={(e) => setEmail(e.target.value)} required />

        <label htmlFor="mdp">Mot de passe</label>
        <input id="mdp" type="password" value={motDePasse} onChange={(e) => setMotDePasse(e.target.value)} required />

        {erreur && <p role="alert" className="erreur">{erreur}</p>}

        <button type="submit" className="bouton">Se connecter</button>
      </form>
    </section>
  )
}

export default Connexion
