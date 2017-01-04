<?php
namespace Vertacoo\LinksDirectoryBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Vertacoo\LinksDirectoryBundle\Form\Type\LinkType;

class LinkController extends Controller
{

    public function listAction(Request $request)
    {
        $linkManager = $this->container->get('vertacoo_links_directory.manager.link');
        
        $links = $linkManager->findLinksBy(array(), array(
            'name' => 'asc'
        ));
        return $this->render('VertacooLinksDirectoryBundle:Admin/Link:list.html.twig', array(
            'links' => $links
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
        
        if ($form->isSubmitted() && $form->isValid()) {
            $linkManager->updateLink($link);
            $this->addFlash('success', $this->get('translator')
                ->trans('admin.link.flash.created', array(), 'vertacoo_links_directory'));
            return new RedirectResponse($this->container->get('router')->generate('vertacoo_links_directory_link_list'));
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
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->container->get('vertacoo_links_directory.manager.link')->updateLink($link);
            $this->addFlash('success', $this->get('translator')
                ->trans('admin.link.flash.updated', array(), 'vertacoo_links_directory'));
            
            return new RedirectResponse($this->container->get('router')->generate('vertacoo_links_directory_link_list', array()));
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