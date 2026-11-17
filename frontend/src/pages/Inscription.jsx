import { useState } from 'react'
import { Link } from 'react-router-dom'
import { envoyer } from '../api.js'

function Inscription() {
  const [nom, setNom] = useState('')
  const [email, setEmail] = useState('')
  const [motDePasse, setMotDePasse] = useState('')
  const [consentement, setConsentement] = useState(false)
  const [erreur, setErreur] = useState('')
  const [reussi, setReussi] = useState(false)

  async function valider(evenement) {
    evenement.preventDefault()
    setErreur('')

    const resultat = await envoyer('POST', '/api/inscription', {
      nom,
      email,
      mot_de_passe: motDePasse,
      consentement,
    })

    if (resultat.ok) {
      setReussi(true)
    } else {
      setErreur(resultat.corps.erreur)
    }
  }

  if (reussi) {
    return (
      <section className="formulaire">
        <h1>Compte créé</h1>
        <p>Votre compte a bien été créé.</p>
        <Link to="/connexion" className="bouton">Se connecter</Link>
      </section>
    )
  }

  return (
    <section className="formulaire">
      <h1>Inscription</h1>
      <form onSubmit={valider}>
        <label htmlFor="nom">Nom</label>
        <input id="nom" type="text" value={nom} onChange={(e) => setNom(e.target.value)} required />

        <label htmlFor="email">Email</label>
        <input id="email" type="email" value={email} onChange={(e) => setEmail(e.target.value)} required />

        <label htmlFor="mdp">Mot de passe (12 caractères minimum)</label>
        <input id="mdp" type="password" value={motDePasse} onChange={(e) => setMotDePasse(e.target.value)} required minLength="12" />

        <label htmlFor="consentement">
          <input id="consentement" type="checkbox" checked={consentement} onChange={(e) => setConsentement(e.target.checked)} />
          J'accepte que mes données soient enregistrées pour gérer mon compte et mes réservations.
        </label>

        {erreur && <p role="alert" className="erreur">{erreur}</p>}

        <button type="submit" className="bouton">Créer mon compte</button>
      </form>
    </section>
  )
}

export default Inscription

