import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { envoyer, envoyerFichier } from '../api.js'

function AdminChambres({ utilisateur }) {
  const [chambres, setChambres] = useState([])
  const [erreur, setErreur] = useState('')
  const [idModif, setIdModif] = useState(null)
  const [nom, setNom] = useState('')
  const [fantome, setFantome] = useState('')
  const [description, setDescription] = useState('')
  const [frayeur, setFrayeur] = useState('')
  const [prix, setPrix] = useState('')
  const [photo, setPhoto] = useState(null)
  const [cleFichier, setCleFichier] = useState(0)

  function charger() {
    fetch('/api/chambres')
      .then((reponse) => {
        if (!reponse.ok) {
          throw new Error('Erreur')
        }
        return reponse.json()
      })
      .then((donnees) => setChambres(donnees))
      .catch(() => setErreur('Impossible de charger les chambres'))
  }

  useEffect(() => {
    if (utilisateur && utilisateur.role === 'admin') {
      charger()
    }
  }, [utilisateur])

  async function supprimer(id) {
    if (!window.confirm('Supprimer cette chambre ?')) {
      return
    }

    setErreur('')
    const resultat = await envoyer('DELETE', `/api/admin/chambres/${id}`)

    if (resultat.ok) {
      charger()
    } else {
      setErreur(resultat.corps.erreur)
    }
  }

  function vider() {
    setIdModif(null)
    setNom('')
    setFantome('')
    setDescription('')
    setFrayeur('')
    setPrix('')
    setPhoto(null)
    setCleFichier(cleFichier + 1)
  }

  function preparerModification(chambre) {
    setIdModif(chambre.id)
    setNom(chambre.nom)
    setFantome(chambre.fantome)
    setDescription(chambre.description)
    setFrayeur(String(chambre.niveau_frayeur))
    setPrix(String(chambre.prix_nuit))
    setPhoto(null)
    setCleFichier((cle) => cle + 1)
  }

  async function enregistrer(evenement) {
    evenement.preventDefault()
    setErreur('')

    const donnees = {
      nom,
      fantome,
      description,
      niveau_frayeur: Number(frayeur),
      prix_nuit: Number(prix),
    }

    let resultat
    if (idModif) {
      resultat = await envoyer('PUT', `/api/admin/chambres/${idModif}`, donnees)
    } else {
      resultat = await envoyer('POST', '/api/admin/chambres', donnees)
    }

    if (!resultat.ok) {
      setErreur(resultat.corps.erreur)
      return
    }

    const idChambre = idModif || resultat.corps.id
    let messagePhoto = ''

    if (photo) {
      const formulaire = new FormData()
      formulaire.append('photo', photo)

      const resultatPhoto = await envoyerFichier(`/api/admin/chambres/${idChambre}/photo`, formulaire)

      if (!resultatPhoto.ok) {
        messagePhoto = `La chambre est enregistrée, mais la photo n'a pas été envoyée : ${resultatPhoto.corps.erreur}. Utilisez « Modifier » pour réessayer.`
      }
    }

    vider()
    charger()
    setErreur(messagePhoto)
  }

  if (!utilisateur || utilisateur.role !== 'admin') {
    return (
      <section>
        <h1>Gestion des chambres</h1>
        <p>Cette page est réservée à l'administrateur.</p>
        <Link to="/">Retour à l'accueil</Link>
      </section>
    )
  }

  return (
    <section className="admin">
      {erreur && <p role="alert" className="erreur">{erreur}</p>}

      <form onSubmit={enregistrer} className="formulaire-admin">
        <h2>{idModif ? 'Modifier la chambre' : 'Ajouter une chambre'}</h2>

        <div className="ligne-formulaire">
          <div className="champ">
            <label htmlFor="nom">Nom de la chambre</label>
            <input id="nom" type="text" value={nom} onChange={(e) => setNom(e.target.value)} required maxLength="100" />
          </div>

          <div className="champ">
            <label htmlFor="fantome">Nom du fantôme</label>
            <input id="fantome" type="text" value={fantome} onChange={(e) => setFantome(e.target.value)} required maxLength="100" />
          </div>
        </div>

        <div className="ligne-formulaire">
          <div className="champ">
            <label htmlFor="frayeur">Niveau de frayeur (1 à 5)</label>
            <input id="frayeur" type="number" min="1" max="5" value={frayeur} onChange={(e) => setFrayeur(e.target.value)} required />
          </div>

          <div className="champ">
            <label htmlFor="prix">Prix par nuit (€)</label>
            <input id="prix" type="number" min="1" max="99999.99" step="0.01" value={prix} onChange={(e) => setPrix(e.target.value)} required />
          </div>
        </div>

        <label htmlFor="description">Histoire de la chambre</label>
        <textarea id="description" value={description} onChange={(e) => setDescription(e.target.value)} required rows="6" />

        <label htmlFor="photo">
          Photo de la chambre (JPG ou PNG, 2 Mo maximum){idModif && ' : laissez vide pour garder la photo actuelle'}
        </label>
        <input
          key={cleFichier}
          id="photo"
          type="file"
          accept="image/jpeg,image/png"
          onChange={(e) => setPhoto(e.target.files[0] || null)}
        />

        <button type="submit" className="bouton">{idModif ? 'Enregistrer' : 'Ajouter'}</button>
        {idModif && <button type="button" onClick={vider}>Annuler la modification</button>}
      </form>

      <h1 className="titre-liste">Gestion des chambres</h1>

      {chambres.map((chambre) => (
        <article key={chambre.id} className="admin-chambre">
          <h2>{chambre.nom}</h2>
          {chambre.photo && (
            <img
              src={`/uploads/${chambre.photo}`}
              alt={`Illustration de ${chambre.nom}`}
              width="1200"
              height="800"
              loading="lazy"
              className="admin-photo"
            />
          )}
          <p>Fantôme : {chambre.fantome}</p>
          <p>Niveau de frayeur : {chambre.niveau_frayeur} / 5</p>
          <p>{chambre.prix_nuit} € la nuit</p>
          <button type="button" onClick={() => preparerModification(chambre)}>Modifier</button>
          <button type="button" onClick={() => supprimer(chambre.id)}>Supprimer</button>
        </article>
      ))}
    </section>
  )
}

export default AdminChambres
