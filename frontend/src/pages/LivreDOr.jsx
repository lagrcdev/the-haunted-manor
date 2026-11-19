import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { envoyer } from '../api.js'

function LivreDOr({ utilisateur }) {
  const [avis, setAvis] = useState([])
  const [erreur, setErreur] = useState('')
  const [note, setNote] = useState('')
  const [texte, setTexte] = useState('')
  const [erreurFormulaire, setErreurFormulaire] = useState('')

  function charger() {
    fetch('/api/avis')
      .then((reponse) => {
        if (!reponse.ok) {
          throw new Error('Erreur')
        }
        return reponse.json()
      })
      .then((donnees) => setAvis(donnees))
      .catch(() => setErreur('Impossible de charger le livre d\'or'))
  }

  useEffect(() => {
    charger()
  }, [])

  async function publier(evenement) {
    evenement.preventDefault()
    setErreurFormulaire('')

    const resultat = await envoyer('POST', '/api/avis', {
      note: Number(note),
      texte,
    })

    if (resultat.ok) {
      setNote('')
      setTexte('')
      charger()
    } else {
      setErreurFormulaire(resultat.corps.erreur)
    }
  }

  return (
    <section className="livre-d-or">
      <h1>Livre d'or</h1>

      {utilisateur ? (
        <form onSubmit={publier} className="formulaire-avis">
          <h2>Laisser un avis</h2>

          <label htmlFor="note">Note (1 à 5)</label>
          <input id="note" type="number" min="1" max="5" value={note} onChange={(e) => setNote(e.target.value)} required />

          <label htmlFor="texte">Votre avis (500 caractères maximum)</label>
          <textarea id="texte" value={texte} onChange={(e) => setTexte(e.target.value)} required maxLength="500" rows="4" />

          {erreurFormulaire && <p role="alert" className="erreur">{erreurFormulaire}</p>}

          <button type="submit" className="bouton">Publier</button>
        </form>
      ) : (
        <p>
          Pour laisser un avis, <Link to="/connexion">connectez-vous</Link> ou{' '}
          <Link to="/inscription">créez un compte</Link>.
        </p>
      )}

      {erreur && <p role="alert" className="erreur">{erreur}</p>}

      {avis.length === 0 && !erreur && <p>Aucun avis pour le moment.</p>}

      {avis.map((unAvis) => (
        <article key={unAvis.id} className="avis">
          <h2>{unAvis.nom}</h2>
          <p>Note : {unAvis.note} / 5</p>
          <p>{unAvis.texte}</p>
          <p>{unAvis.date}</p>
        </article>
      ))}
    </section>
  )
}

export default LivreDOr
