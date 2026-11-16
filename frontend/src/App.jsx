import { Route, Routes } from 'react-router-dom'
import Entete from './components/Entete.jsx'
import PiedDePage from './components/PiedDePage.jsx'
import Accueil from './pages/Accueil.jsx'
import Introuvable from './pages/Introuvable.jsx'
import Chambres from './pages/Chambres.jsx'

function App() {
    return (
    <>
      <Entete />
      <main>
        <Routes>
          <Route path="/" element={<Accueil />} />
          <Route path="/chambres" element={<Chambres />} />
          <Route path="*" element={<Introuvable />} />
        </Routes>
      </main>
      <PiedDePage />
    </>
  )
}

export default App
