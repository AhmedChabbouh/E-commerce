<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\AnimalProduct;
use App\Entity\Category;

#[Route( '/product', name: 'app_product')]
final class ProductController extends AbstractController
{
    #[Route('/list/{categoryName}', name: 'product_list')]
    public function show_products(ManagerRegistry $doctrine,String  $categoryName): Response
    {   $entityManager = $doctrine->getManager();
        $category=$entityManager->getRepository(Category::class)->findOneBy(['name'=>$categoryName]);

        $repo = $doctrine->getRepository(Product::class);
        $products = $repo->findBy(['category'=>$category]);
        foreach ($products as $product) {
            if ($product->getStockQuantity()==0) {
                $products.remove($product);
            }
        }
        return $this->render('product/list.html.twig', [
            'products' =>$products
        ]);
    }

    #[Route('/addAnimal', name: 'product_add')]
public function add_product(ManagerRegistry $doctrine):Response{
        $entityManager = $doctrine->getManager();
        $repo = $doctrine->getRepository(AnimalProduct::class);
        $category = $doctrine->getRepository(Category::class)->findOneBy(['name' => 'Chien']);
        $category1 = $doctrine->getRepository(Category::class)->findOneBy(['name' => 'Rongeurs']);
        $category2= $doctrine->getRepository(Category::class)->findOneBy(['name' => 'Chats']);
        $category3 = $doctrine->getRepository(Category::class)->findOneBy(['name' => 'Oiseau']);
        $category4 = $doctrine->getRepository(Category::class)->findOneBy(['name' => 'poisson']);




        $animal = new AnimalProduct();

        $animal->setName('Bobby');
        $animal->setPrice(1000);
        $animal->setCategory($category);
        $animal->setDescription('petit chien Chiwawah marron');
        $animal->setBreed('Chien');
        $animal->setSpecies('Chiwahwah');
        $animal->setGender('Male');
        $animal->setAge("1ans" );
        $animal->setImage("https://pbs.twimg.com/media/EkraWTyU0AA3wZX.png");
        $animal->setStockQuantity(3);
        $animal->setInfo("un mâle très doux affectueux et joueur .il pourra rejoindre sa nouvelle famille à partir du 11 juin, vacciné, pucé , certificat de bonne santé délivré par le vétérinaire.");







        $animal1 = new AnimalProduct();

        $animal1->setName('Mbappe & Maradonna');
        $animal1->setPrice(1000);
        $animal1->setCategory($category1);
        $animal1->setDescription('Lapin petit avec velours gris');
        $animal1->setBreed('Rongeurs');
        $animal1->setSpecies('lapin belier');
        $animal1->setGender('Male');
        $animal1->setAge("1 mois");
        $animal1->setImage("https://images.unsplash.com/photo-1576502733340-710601fc1838?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");
        $animal1->setStockQuantity(3);
        $animal->setInfo("Le lapin bélier, ou lope, est une variété de lapin domestique, se distinguant principalement des autres par ses oreilles tombantes. Sa fourrure est soyeuse et assez dense, ce qui donne souvent à l'animal une apparence de peluche trapue");



        $animal2 = new AnimalProduct();

        $animal2->setName('Doudi');
        $animal2->setPrice(1000);
        $animal2->setCategory($category1);
        $animal2->setDescription('Lapin petit avec velours gris');
        $animal2->setBreed('Rongeurs');
        $animal2->setSpecies('lapin belier');
        $animal2->setGender('Male');
        $animal2->setAge("2 mois");
        $animal2->setImage("https://images.unsplash.com/photo-1578164252864-480fe16ef05e?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");
        $animal2->setStockQuantity(3);
        $animal2->setInfo("Le lapin bélier, ou lope, est une variété de lapin domestique, se distinguant principalement des autres par ses oreilles tombantes. Sa fourrure est soyeuse et assez dense, ce qui donne souvent à l'animal une apparence de peluche trapue");




















        //$doctrine->getManager()->persist($animal);
       // $doctrine->getManager()->persist($animal1);
        //$doctrine->getManager()->persist($animal2);



        $animal3 = new AnimalProduct();

        $animal3->setName('Sousou');
        $animal3->setPrice(1500);
        $animal3->setCategory($category1);
        $animal3->setDescription('Lapin petit avec velours blancs');
        $animal3->setBreed('Rongeurs');
        $animal3->setSpecies('lapin angora anglais');
        $animal3->setGender('Male');
        $animal3->setAge("1 semaine");
        $animal3->setImage("https://images.unsplash.com/photo-1578164252392-83ddb77de36c?q=80&w=2071&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");
        $animal3->setStockQuantity(1);
        $animal3->setInfo("Le lapin Angora anglais est une race crée par l’homme en turquie comme son copain le chat angora. Son poil n’est donc pas une origine naturelle. C’est pourquoi votre compagnon aura besoin de vous pour l’aider à s’occuper de sa longue toison.");



        $animal4 = new AnimalProduct();

        $animal4->setName('kalboush');
        $animal4->setPrice(700);
        $animal4->setCategory($category);
        $animal4->setInfo('Couleur approximative à l’âge adulte, blanc ou crème. Ils sont habitués aux bruits et à la vie quotidienne de la maison et du jardin.

Ils commencent à être propre. Ils partiront avec un Pedigree Internationale, vaccin, puce électronique et le paquet de croquettes auquel ils sont pour l’instant habitués.');
        $animal4->setBreed('Chien');
        $animal4->setSpecies('Golden Retreiver');
        $animal4->setGender('Male');
        $animal4->setAge("5 mois");
        $animal4->setImage("https://www.fonddelanoye.fr/media/cache/conversions/breeder/434/breeding/elevage-du-fond-de-la-noye/animal/golden-retriever-collier-violet-2/photos/49315/chien-golden-retriever-rmione-elevage-du-fond-de-la-noye-0-big.jpg");
        $animal4->setStockQuantity(4);
        $animal4->setDescription("chien avec velours marron");



        $animal5 = new AnimalProduct();

        $animal5->setName('Nimo');
        $animal5->setPrice(1700);
        $animal5->setCategory($category4);
        $animal5->setDescription('un  petit clown fish oranger et blanc !');
        $animal5->setBreed('Poisson');
        $animal5->setSpecies('Clown Fish');
        $animal5->setGender('Female');
        $animal5->setAge("55 jours");
        $animal5->setImage("https://images.unsplash.com/photo-1535591273668-578e31182c4f?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");
        $animal5->setStockQuantity(1);
        $animal5->setInfo("Les poissons-clowns, ou Amphiprioninae, sont une sous-famille de poissons appartenant à la famille des pomacentridés. Elle contient trente espèces faisant toutes partie du genre Amphiprion. Ce sont des poissons d'une dizaine de centimètres dans les tons d'orange et de noir.");



        $animal6 = new AnimalProduct();
        $animal6->setName('Mimoune');
        $animal6->setPrice(1200);
        $animal6->setCategory($category2); // Chat
        $animal6->setDescription("Chaton gris");
        $animal6->setBreed('Chat');
        $animal6->setSpecies('British Shorthair');
        $animal6->setGender('Female');
        $animal6->setAge("3 mois");
        $animal6->setStockQuantity(3);$animal6->setImage("https://cdn.shopify.com/s/files/1/0265/1327/7008/files/chaton-british-shorthair.jpg?v=1698228698");
        $animal6->setInfo("Les poissons-clowns, ou Amphiprioninae, sont une sous-famille de poissons appartenant à la famille des pomacentridés. Elle contient trente espèces faisant toutes partie du genre Amphiprion. Ce sont des poissons d'une dizaine de centimètres dans les tons d'orange et de noir.");





        $animal7 = new AnimalProduct();
        $animal7->setName('Titi');
        $animal7->setPrice(500);
        $animal7->setCategory($category3); // Oiseau
        $animal7->setDescription("Petit canari jaune vif");
        $animal7->setBreed('Oiseau');
        $animal7->setSpecies('Canari');
        $animal7->setGender('Male');
        $animal7->setAge("6 mois");
        $animal7->setStockQuantity(4);
        $animal7->setImage("https://plus.unsplash.com/premium_photo-1726769152920-105b836eb2b0?q=80&w=1937&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");
        $animal7->setInfo("Le canari jaune est une variété de canari domestique très populaire, connue pour son plumage jaune vif, qui a donné son nom à la couleur . Ces oiseaux sont souvent utilisés comme animaux de compagnie et peuvent vivre dans une cage ou une volière intérieure ou extérieure. ");







        $animal8 = new AnimalProduct();
        $animal8->setName('Bulle');
        $animal8->setPrice(950);
        $animal8->setCategory($category4); // Poisson
        $animal8->setDescription("Un petit poisson combattant aux reflets bleus et violets. Idéal pour débuter un aquarium.");
        $animal8->setBreed('Poisson');
        $animal8->setSpecies('Betta splendens');
        $animal8->setGender('Male');
        $animal8->setAge("2 mois");
        $animal8->setStockQuantity(5);
       $animal8->setImage("https://images.unsplash.com/photo-1573472420143-0c68f179bdc7?q=80&w=2094&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");
       $animal8->setInfo("Les poissons bulles sont des créatures incroyablement polyvalentes en raison de leur capacité à voyager dans une bulle d'eau à travers divers environnements aquatiques et même aériens. Cette aptitude leur permet de chasser non seulement sous l'eau, mais aussi en dehors de celle-ci.");










      //  $doctrine->getManager()->persist($animal3);
       // $doctrine->getManager()->persist($animal4);
        // $doctrine->getManager()->persist($animal5);
      // $doctrine->getManager()->persist($animal8);
      //  $doctrine->getManager()->persist($animal6);
       // $doctrine->getManager()->persist($animal7);



        $animal9 = new AnimalProduct();
        $animal9->setName('Flamme');
        $animal9->setPrice(1200);
        $animal9->setCategory($category3);
        $animal9->setDescription("Un perroquet intelligent aux plumes rouges flamboyantes.");
        $animal9->setBreed('Perroquet');
        $animal9->setSpecies('Ara macao');
        $animal9->setGender('Femelle');
        $animal9->setAge("1 an");
        $animal9->setStockQuantity(3);
        $animal9->setInfo("Les Aras macao sont des oiseaux très intelligents, capables de reproduire des sons humains et d’apprendre des tours. Ils nécessitent une attention quotidienne et un grand espace de vol.");
        $animal9->setImage("https://images.unsplash.com/photo-1544923408-75c5cef46f14?q=80&w=1964&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");

        $animal10 = new AnimalProduct();
        $animal10->setName('Noisette');
        $animal10->setPrice(350);
        $animal10->setCategory($category1);
        $animal10->setDescription("Un adorable hamster nain très sociable et curieux.");
        $animal10->setBreed('Hamster');
        $animal10->setSpecies('Phodopus sungorus');
        $animal10->setGender('Femelle');
        $animal10->setAge("3 mois");
        $animal10->setStockQuantity(8);
        $animal10->setInfo("Le hamster nain russe est un petit rongeur vif, facile à entretenir, et adapté aux enfants. Il aime explorer et se cache souvent dans ses tunnels.");
        $animal10->setImage("https://images.unsplash.com/photo-1738504822384-f72a0d95bd76?q=80&w=1931&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");


        $animal11 = new AnimalProduct();
        $animal11->setName('Michou');
        $animal11->setPrice(2900);
        $animal11->setCategory($category2);
        $animal11->setDescription("Chaton blanc très affectueux, idéal pour la vie en famille.");
        $animal11->setBreed('Chat');
        $animal11->setSpecies('Chat turque');
        $animal11->setGender('Male');
        $animal11->setAge("4 mois");
        $animal11->setStockQuantity(4);
        $animal11->setInfo("Le chat Turque est connu pour son tempérament calme et doux. Très attaché à son foyer, il est peu exigeant et adore les câlins.");
       $animal11->setImage(" https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcRo8s2CN23pyklRfAM5_mZky1dAzDCYB8LScnriqg38gchGODRv0VcQqm6U1I8nv_gMs3afbTWrISOqbLhK4ya4qz7Mi7b33k4S3_k61e4");

        $animal12 = new AnimalProduct();
        $animal12->setName('Tornado');
        $animal12->setPrice(1300);
        $animal12->setCategory($category);
        $animal12->setDescription("Chiot énergique parfait pour une vie active.");
        $animal12->setBreed('Border Collie');
        $animal12->setSpecies('Canis lupus familiaris');
        $animal12->setGender('Male');
        $animal12->setAge("5 mois");
        $animal12->setStockQuantity(2);
        $animal12->setInfo("Le Border Collie est l’une des races les plus intelligentes et actives. Il a besoin de beaucoup d’exercice et d’activités mentales.");
        $animal12->setImage("https://www.la-spa.fr/app/app/uploads/2021/09/Border-collie-Deskop-2.jpg");

        $animal13 = new AnimalProduct();
        $animal13->setName('Luna');
        $animal13->setPrice(980);
        $animal13->setCategory($category2);
        $animal13->setDescription("Chatte élégante aux yeux bleus, douce et calme.");
        $animal13->setBreed('Siamois');
        $animal13->setSpecies('Felis catus');
        $animal13->setGender('Femelle');
        $animal13->setAge("6 mois");
        $animal13->setStockQuantity(5);
        $animal13->setInfo("Le Siamois est un chat affectueux, communicatif et élégant. Il aime être entouré et suit souvent son maître partout.");
        $animal13->setImage("https://images.unsplash.com/photo-1596182745879-f2ad2337f706?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");



        $animal14 = new AnimalProduct();
        $animal14->setName('Splash');
        $animal14->setPrice(300);
        $animal14->setCategory($category4);
        $animal14->setDescription("Poisson coloré, parfait pour les petits aquariums.");
        $animal14->setBreed('Guppy');
        $animal14->setSpecies('Poecilia reticulata');
        $animal14->setGender('Femelle');
        $animal14->setAge("1 mois");
        $animal14->setStockQuantity(15);
        $animal14->setInfo("Le guppy est un poisson d'eau douce très facile à élever. Il est idéal pour les débutants et se reproduit rapidement.");
        $animal14->setImage("https://www.garnelio.fr/media/image/69/dd/4c/IMG-3705Z-1_600x600.jpg");



        $animal15 = new AnimalProduct();
        $animal15->setName('Pikachu');
        $animal15->setPrice(400);
        $animal15->setCategory($category1);
        $animal15->setDescription("Petit cochon d’Inde au pelage tacheté.");
        $animal15->setBreed('Cochon d’Inde');
        $animal15->setSpecies('Cavia porcellus');
        $animal15->setGender('Male');
        $animal15->setAge("2 mois");
        $animal15->setStockQuantity(6);
        $animal15->setInfo("Les cochons d’Inde sont des animaux sociaux et affectueux, parfaits pour les enfants. Ils aiment vivre à deux ou en groupe.");
        $animal15->setImage("https://www.omvq.qc.ca/DATA/CONSEIL/12_fr.jpg");




        $animal16 = new AnimalProduct();
        $animal16->setName('Bouzayen');
        $animal16->setPrice(1000);
        $animal16->setCategory($category3);
        $animal16->setDescription("Canari chanteur aux plumes bleus ciel .");
        $animal16->setBreed('Canari');
        $animal16->setSpecies('Serinus canaria');
        $animal16->setGender('Male');
        $animal16->setAge("9 mois");
        $animal16->setStockQuantity(10);
        $animal16->setInfo("Le canari est un petit oiseau apprécié pour ses chants mélodieux. Il nécessite une cage spacieuse et une alimentation variée.");
        $animal16->setImage("https://lh3.googleusercontent.com/proxy/UkNyUUh-KC6-Cz2o-PIKJVsEhcPHxAL2SUP78_lvieQSZukjj1kvHi7PyGS31Kv24QbuVBYco85oeueFGlpiC8DTyaKrOSC8D1JNXbEmWFkSJg");



        $animal17 = new AnimalProduct();
        $animal17->setName('Shadow');
        $animal17->setPrice(1600);
        $animal17->setCategory($category);
        $animal17->setDescription("Chiot noir fidèle et protecteur.");
        $animal17->setBreed('Labrador Retriever');
        $animal17->setSpecies('Canis lupus familiaris');
        $animal17->setGender('Male');
        $animal17->setAge("7 mois");
        $animal17->setStockQuantity(3);
        $animal17->setInfo("Le Labrador est un chien affectueux, obéissant et excellent avec les enfants. Il a besoin d’exercice et adore l’eau.");
        $animal17->setImage("https://as2.ftcdn.net/jpg/05/41/02/47/1000_F_541024726_dsKJkCBL8CZlEHYbS2LNS0xy10vbF55s.jpg");




        $animal18 = new AnimalProduct();
        $animal18->setName('Frimousse');
        $animal18->setPrice(450);
        $animal18->setCategory($category1);
        $animal18->setDescription("Lapin nain doux au pelage soyeux.");
        $animal18->setBreed('Lapin nain');
        $animal18->setSpecies('Oryctolagus cuniculus');
        $animal18->setGender('Femelle');
        $animal18->setAge("4 mois");
        $animal18->setStockQuantity(7);
        $animal18->setInfo("Les lapins nains sont calmes et joueurs. Ils peuvent vivre en liberté dans la maison, à condition d’être surveillés.");
        $animal18->setImage("https://images.prismic.io/nationalparks/b5f776c8-7ee3-4fbd-95f7-5d104f906b77_european-rabbit.jpeg?auto=compress,format");









        $entityManager->persist($animal9);
        $entityManager->persist($animal10);
        $entityManager->persist($animal11);
        $entityManager->persist($animal12);
        $entityManager->persist($animal13);
        $entityManager->persist($animal14);
        $entityManager->persist($animal15);
        $entityManager->persist($animal16);
        $entityManager->persist($animal17);
        $entityManager->persist($animal18);

        $entityManager->flush();




        $doctrine->getManager()->flush();
        return new Response('Animal product added!');
    }

}
