<?php
namespace Vertacoo\LinksDirectoryBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Vertacoo\LinksDirectoryBundle\Form\Type\LinkType;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Vertacoo\LinksDirectoryBundle\Form\Type\LinkFilterType;

class LinkController extends Controller
{

    public function listAction(Request $request, $page = 1)
    {
        $linkManager = $this->container->get('vertacoo_links_directory.manager.link');
        $categoryManager = $this->container->get('vertacoo_links_directory.manager.category');
        
        $maxLinksPerPage = $this->getParameter('vertacoo_links_directory.max_links_per_page');
        
        $filter = $request->query->get('vertacoo_links_linkfilter');
        $linksQuery = $linkManager->getLinksQuery('name', 'asc', $maxLinksPerPage, ($page - 1) * $maxLinksPerPage, $filter);
        $links = new Paginator($linksQuery, false);
        
        $pagination = array(
            'page' => $page,
            'pages_count' => ceil(count($links) / $maxLinksPerPage),
            'route' => 'vertacoo_links_directory_link_list',
            'route_params' => array()
        );
        
        $filterForm = $this->createForm(LinkFilterType::class, array(
            'name' => $filter['name'],
            'categorie' => $categoryManager->findCategoryBy(array('id'=>$filter['categorie'])),
        ));
        return $this->render('VertacooLinksDirectoryBundle:Admin/Link:list.html.twig', array(
            'links' => $links,
            'pagination' => $pagination,
            'filter' => $filterForm->createView()
        ));
    }

    public function showAction($id)
    {
        $link = $this->findLinkById($id);
        return $this->render('VertacooLinksDirectoryBundle:Admin/Link:show.html.twig', array(
            'link' => $link
        ));
    }

    /**
     * Create a new category: show the new form.
     */
    public function newAction(Request $request)
    {
        $linkManager = $this->container->get('vertacoo_links_directory.manager.link');
        
        $link = $linkManager->createLink();
        
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $linkManager->updateLink($link);
                $this->addFlash('success', $this->get('translator')
                    ->trans('admin.link.flash.created', array(), 'vertacoo_links_directory'));
                return new RedirectResponse($this->container->get('router')->generate('vertacoo_links_directory_link_list'));
            } else {
                $this->addFlash('danger', $this->get('translator')
                    ->trans('admin.link.flash.error', array(), 'vertacoo_links_directory'));
            }
        }
        
        return $this->render('VertacooLinksDirectoryBundle:Admin/Link:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Edit a category: show the edit form.
     */
    public function editAction(Request $request, $id)
    {
        $link = $this->findLinkById($id);
        
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->container->get('vertacoo_links_directory.manager.link')->updateLink($link);
                $this->addFlash('success', $this->get('translator')
                    ->trans('admin.link.flash.updated', array(), 'vertacoo_links_directory'));
                
                return new RedirectResponse($this->container->get('router')->generate('vertacoo_links_directory_link_list', array()));
            } else {
                $this->addFlash('danger', $this->get('translator')
                    ->trans('admin.link.flash.error', array(), 'vertacoo_links_directory'));
            }
        }
        return $this->render('VertacooLinksDirectoryBundle:Admin/Link:edit.html.twig', array(
            'link' => $link,
            'form' => $form->createView()
        ));
    }

    /**
     * Delete a category.
     */
    public function deleteAction($id)
    {
        $link = $this->findLinkById($id);
        
        $this->container->get('vertacoo_links_directory.manager.link')->deleteLink($link);
        $this->addFlash('success', $this->get('translator')
            ->trans('admin.link.flash.deleted', array(), 'vertacoo_links_directory'));
        
        return new RedirectResponse($this->container->get('router')->generate('vertacoo_links_directory_link_list'));
    }

    /**
     * Move a category.
     */
    public function moveAction(Request $request, $id)
    {
        $parentId = null;
        $category = $this->findCategoryById($id);
        if ($request->query->has('parentId')) {
            $parentId = $request->query->get('parentId');
        }
        if ($request->query->has('direction')) {
            $direction = $request->query->get('direction');
            if ($direction == 'up') {
                $category->setPosition($category->getPosition() - 1);
            } else {
                $category->setPosition($category->getPosition() + 1);
            }
            
            $this->container->get('vertacoo_links_directory.manager.category')->updateCategory($category);
        }
        return new RedirectResponse($this->container->get('router')->generate('vertacoo_links_directory_category_list', array(
            'parentId' => $parentId
        )));
    }

    /**
     * Finds a category by id.
     *
     * @param mixed $id            
     *
     * @return CategoryInterface
     *
     * @throws NotFoundHttpException When category does not exist
     */
    protected function findLinkById($id)
    {
        $link = $this->container->get('vertacoo_links_directory.manager.link')->findLinkBy(array(
            'id' => $id
        ));
        if (null === $link) {
            throw new NotFoundHttpException(sprintf('The link with id %s does not exist', $id));
        }
        return $link;
    }
}
