import { useEffect, useState } from 'react'
import { Route, Routes } from 'react-router-dom'
import Entete from './components/Entete.jsx'
import PiedDePage from './components/PiedDePage.jsx'
import Accueil from './pages/Accueil.jsx'
import Introuvable from './pages/Introuvable.jsx'
import Chambres from './pages/Chambres.jsx'
import ChambreDetail from './pages/ChambreDetail.jsx'
import Inscription from './pages/Inscription.jsx'
import Connexion from './pages/Connexion.jsx'
import { envoyer } from './api.js'

function App() {
  const [utilisateur, setUtilisateur] = useState(null)

  useEffect(() => {
    fetch('/api/moi')
      .then((reponse) => (reponse.ok ? reponse.json() : null))
      .then((donnees) => setUtilisateur(donnees))
  }, [])

  async function deconnecter() {
    await envoyer('POST', '/api/deconnexion')
    setUtilisateur(null)
  }

  return (
    <>
      <Entete utilisateur={utilisateur} onDeconnexion={deconnecter} />
      <main>
        <Routes>
          <Route path="/" element={<Accueil />} />
          <Route path="/chambres" element={<Chambres />} />
          <Route path="/chambres/:id" element={<ChambreDetail />} />
          <Route path="/inscription" element={<Inscription />} />
          <Route path="/connexion" element={<Connexion onConnexion={setUtilisateur} />} />
          <Route path="*" element={<Introuvable />} />
        </Routes>
      </main>
      <PiedDePage />
    </>
  )
}

export default App
