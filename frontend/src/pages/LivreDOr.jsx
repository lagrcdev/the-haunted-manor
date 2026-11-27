import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { envoyer } from '../api.js'

function formaterDate(date) {
  const [jour, heure] = date.split(' ')
  const [annee, mois, numero] = jour.split('-')
  return `${numero}/${mois}/${annee.slice(2)} ${heure.slice(0, 5)}`
}

function LivreDOr({ utilisateur }) {
  const [avis, setAvis] = useState([])
  const [erreur, setErreur] = useState('')
  const [chargement, setChargement] = useState(true)
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
      .finally(() => setChargement(false))
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

  async function supprimer(id) {
    if (!window.confirm('Supprimer votre avis ?')) {
      return
    }

    const resultat = await envoyer('DELETE', `/api/avis/${id}`)

    if (resultat.ok) {
      charger()
    } else {
      setErreur(resultat.corps.erreur)
    }
  }

  return (
    <section className="livre-d-or">
      <h1>Livre d'or</h1>

      <div className="livre-contenu">
        <div className="livre-formulaire">
          {utilisateur ? (
            <form onSubmit={publier} className="formulaire-avis">
              <h2>Laisser un avis</h2>

              <label htmlFor="note">Note</label>
              <input id="note" type="number" min="1" max="5" placeholder="1-5" value={note} onChange={(e) => setNote(e.target.value)} required />

              <label htmlFor="texte">Votre avis (500 caractères maximum)</label>
              <textarea id="texte" value={texte} onChange={(e) => setTexte(e.target.value)} required maxLength="500" rows="6" />

              {erreurFormulaire && <p role="alert" className="erreur">{erreurFormulaire}</p>}

              <button type="submit" className="bouton">Publier</button>
            </form>
          ) : (
            <p>
              Pour laisser un avis, <Link to="/connexion">connectez-vous</Link> ou{' '}
              <Link to="/inscription">créez un compte</Link>.
            </p>
          )}
        </div>

        <div className="livre-liste">
          {erreur &&<p role="alert" className="erreur">{erreur}</p>}

          {chargement && !erreur && <p>Chargement…</p>}

          {!chargement && avis.length === 0 && !erreur && <p>Aucun avis pour le moment.</p>}

          {avis.map((unAvis) => (
            <article key={unAvis.id} className="avis">
              <div className="avis-entete">
                <h2>{unAvis.nom}</h2>
                <p className="avis-date">{formaterDate(unAvis.date)}</p>
              </div>
              <p className="note">Note : {unAvis.note} / 5</p>
              <p>{unAvis.texte}</p>
              {unAvis.mien && (
                <button type="button" onClick={() => supprimer(unAvis.id)}>Supprimer mon avis</button>
              )}
            </article>
          ))}
        </div>
      </div>
    </section>
  )
}

export default LivreDOr
