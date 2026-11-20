import { useEffect, useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import { envoyer } from '../api.js'

function ChambreDetail({ utilisateur }) {
  const { id } = useParams()
  const [chambre, setChambre] = useState(null)
  const [erreur, setErreur] = useState('')
  const [arrivee, setArrivee] = useState('')
  const [depart, setDepart] = useState('')
  const [erreurReservation, setErreurReservation] = useState('')
  const [confirmation, setConfirmation] = useState(null)

  useEffect(() => {
    fetch(`/api/chambres/${id}`)
      .then((reponse) => {
        if (!reponse.ok) {
          throw new Error('Chambre introuvable')
        }
        return reponse.json()
      })
      .then((donnees) => setChambre(donnees))
      .catch(() => setErreur('Chambre introuvable'))
  }, [id])

  async function reserver(evenement) {
    evenement.preventDefault()
    setErreurReservation('')

    const resultat = await envoyer('POST', '/api/reservations', {
      chambre_id: chambre.id,
      date_arrivee: arrivee,
      date_depart: depart,
    })

    if (resultat.ok) {
      setConfirmation(resultat.corps)
    } else {
      setErreurReservation(resultat.corps.erreur)
    }
  }

  if (erreur) {
    return <p>{erreur}</p>
  }

  if (!chambre) {
    return <p>Chargement...</p>
  }

  return (
    <section className="detail-chambre">
      <h1>{chambre.nom}</h1>
      {chambre.photo && (
        <img
          src={`/uploads/${chambre.photo}`}
          alt={`Illustration de ${chambre.nom}`}
          width="1200"
          height="800"
          className="photo-chambre"
        />
      )}
      <div className="infos-chambre">
        <p>Fantôme : {chambre.fantome}</p>
        <hr className="trait" />
        <p className="histoire">{chambre.description}</p>
        <p>2 personnes</p>
        <div className="infos-bas">
          <p>Niveau de frayeur : {chambre.niveau_frayeur} / 5</p>
          <p>{chambre.prix_nuit} € la nuit</p>
        </div>
      </div>

      {confirmation ? (
        <div className="confirmation" role="status">
          <h2>Réservation confirmée</h2>
          <p>
            Du {arrivee} au {depart} : {confirmation.nuits} nuit(s),
            total {confirmation.prix_total} €.
          </p>
        </div>
      ) : utilisateur ? (
        <form onSubmit={reserver} className="formulaire-reservation">
          <h2>Réserver cette chambre</h2>

          <label htmlFor="arrivee">Date d'arrivée</label>
          <input id="arrivee" type="date" value={arrivee} onChange={(e) => setArrivee(e.target.value)} required />

          <label htmlFor="depart">Date de départ</label>
          <input id="depart" type="date" value={depart} onChange={(e) => setDepart(e.target.value)} required />

          {erreurReservation && <p role="alert" className="erreur">{erreurReservation}</p>}

          <button type="submit" className="bouton">Réserver</button>
        </form>
      ) : (
        <p>
          Pour réserver, <Link to="/connexion">connectez-vous</Link> ou{' '}
          <Link to="/inscription">créez un compte</Link>.
        </p>
      )}

      <Link to="/chambres" className="bouton">Retour aux chambres</Link>
    </section>
  )
}

export default ChambreDetail
